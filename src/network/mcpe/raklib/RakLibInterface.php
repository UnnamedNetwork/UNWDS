<?php

/*
 *
 *  ____            _        _   __  __ _                  __  __ ____
 * |  _ \ ___   ___| | _____| |_|  \/  (_)_ __   ___      |  \/  |  _ \
 * | |_) / _ \ / __| |/ / _ \ __| |\/| | | '_ \ / _ \_____| |\/| | |_) |
 * |  __/ (_) | (__|   <  __/ |_| |  | | | | | |  __/_____| |  | |  __/
 * |_|   \___/ \___|_|\_\___|\__|_|  |_|_|_| |_|\___|     |_|  |_|_|
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Lesser General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * @author PocketMine Team
 * @link http://www.pocketmine.net/
 *
 *
*/

declare(strict_types=1);

namespace pocketmine\network\mcpe\raklib;

use pocketmine\network\AdvancedNetworkInterface;
use pocketmine\network\mcpe\compression\ZlibCompressor;
use pocketmine\network\mcpe\convert\TypeConverter;
use pocketmine\network\mcpe\NetworkSession;
use pocketmine\network\mcpe\PacketBroadcaster;
use pocketmine\network\mcpe\protocol\PacketPool;
use pocketmine\network\mcpe\protocol\ProtocolInfo;
use pocketmine\network\mcpe\StandardPacketBroadcaster;
use pocketmine\network\Network;
use pocketmine\network\NetworkInterfaceStartException;
use pocketmine\network\PacketHandlingException;
use pocketmine\Server;
use pocketmine\snooze\SleeperNotifier;
use pocketmine\utils\Utils;
use raklib\generic\SocketException;
use raklib\protocol\EncapsulatedPacket;
use raklib\protocol\PacketReliability;
use raklib\server\ipc\RakLibToUserThreadMessageReceiver;
use raklib\server\ipc\UserToRakLibThreadMessageSender;
use raklib\server\ServerEventListener;
use raklib\utils\InternetAddress;
use function addcslashes;
use function base64_encode;
use function bin2hex;
use function implode;
use function mt_rand;
use function random_bytes;
use function rtrim;
use function substr;

class RakLibInterface implements ServerEventListener, AdvancedNetworkInterface{
	/**
	 * Sometimes this gets changed when the MCPE-layer protocol gets broken to the point where old and new can't
	 * communicate. It's important that we check this to avoid catastrophes.
	 */
	private const MCPE_RAKNET_PROTOCOL_VERSION = 10;

	private const MCPE_RAKNET_PACKET_ID = "\xfe";

	/** @var Server */
	private $server;

	/** @var Network */
	private $network;

	/** @var int */
	private $rakServerId;

	/** @var RakLibServer */
	private $rakLib;

	/** @var NetworkSession[] */
	private $sessions = [];

	/** @var RakLibToUserThreadMessageReceiver */
	private $eventReceiver;
	/** @var UserToRakLibThreadMessageSender */
	private $interface;

	/** @var SleeperNotifier */
	private $sleeper;

	/** @var PacketBroadcaster */
	private $broadcaster;

	public function __construct(Server $server, string $ip, int $port, bool $ipV6){
		$this->server = $server;
		$this->rakServerId = mt_rand(0, PHP_INT_MAX);

		$this->sleeper = new SleeperNotifier();

		$mainToThreadBuffer = new \Threaded;
		$threadToMainBuffer = new \Threaded;

		$this->rakLib = new RakLibServer(
			$this->server->getLogger(),
			$mainToThreadBuffer,
			$threadToMainBuffer,
			new InternetAddress($ip, $port, $ipV6 ? 6 : 4),
			$this->rakServerId,
			$this->server->getConfigGroup()->getPropertyInt("network.max-mtu-size", 1492),
			self::MCPE_RAKNET_PROTOCOL_VERSION,
			$this->sleeper
		);
		$this->eventReceiver = new RakLibToUserThreadMessageReceiver(
			new PthreadsChannelReader($threadToMainBuffer)
		);
		$this->interface = new UserToRakLibThreadMessageSender(
			new PthreadsChannelWriter($mainToThreadBuffer)
		);

		$this->broadcaster = new StandardPacketBroadcaster($this->server);
	}

	public function start() : void{
		$this->server->getTickSleeper()->addNotifier($this->sleeper, function() : void{
			while($this->eventReceiver->handle($this));
		});
		$this->server->getLogger()->debug("Waiting for RakLib to start...");
		try{
			$this->rakLib->startAndWait();
		}catch(SocketException $e){
			throw new NetworkInterfaceStartException($e->getMessage(), 0, $e);
		}
		$this->server->getLogger()->debug("RakLib booted successfully");
	}

	public function setNetwork(Network $network) : void{
		$this->network = $network;
	}

	public function tick() : void{
		if(!$this->rakLib->isRunning()){
			$e = $this->rakLib->getCrashInfo();
			if($e !== null){
				throw new \RuntimeException("RakLib crashed: " . $e->makePrettyMessage());
			}
			throw new \Exception("RakLib Thread crashed without crash information");
		}
	}

	public function onClientDisconnect(int $sessionId, string $reason) : void{
		if(isset($this->sessions[$sessionId])){
			$session = $this->sessions[$sessionId];
			unset($this->sessions[$sessionId]);
			$session->onClientDisconnect($reason);
		}
	}

	public function close(int $sessionId) : void{
		if(isset($this->sessions[$sessionId])){
			unset($this->sessions[$sessionId]);
			$this->interface->closeSession($sessionId);
		}
	}

	public function shutdown() : void{
		$this->server->getTickSleeper()->removeNotifier($this->sleeper);
		$this->rakLib->quit();
	}

	public function onClientConnect(int $sessionId, string $address, int $port, int $clientID) : void{
		$session = new NetworkSession(
			$this->server,
			$this->network->getSessionManager(),
			PacketPool::getInstance(),
			new RakLibPacketSender($sessionId, $this),
			$this->broadcaster,
			ZlibCompressor::getInstance(), //TODO: this shouldn't be hardcoded, but we might need the RakNet protocol version to select it
			$address,
			$port
		);
		$this->sessions[$sessionId] = $session;
	}

	public function onPacketReceive(int $sessionId, string $packet) : void{
		if(isset($this->sessions[$sessionId])){
			if($packet === "" or $packet[0] !== self::MCPE_RAKNET_PACKET_ID){
				$this->sessions[$sessionId]->getLogger()->debug("Non-FE packet received: " . base64_encode($packet));
				return;
			}
			//get this now for blocking in case the player was closed before the exception was raised
			$session = $this->sessions[$sessionId];
			$address = $session->getIp();
			$buf = substr($packet, 1);
			try{
				$session->handleEncoded($buf);
			}catch(PacketHandlingException $e){
				$errorId = bin2hex(random_bytes(6));

				$logger = $session->getLogger();
				$logger->error("Bad packet (error ID $errorId): " . $e->getMessage());

				//intentionally doesn't use logException, we don't want spammy packet error traces to appear in release mode
				$logger->debug(implode("\n", Utils::printableExceptionInfo($e)));
				$session->disconnect("Packet processing error (Error ID: $errorId)");
				$this->interface->blockAddress($address, 5);
			}
		}
	}

	public function blockAddress(string $address, int $timeout = 300) : void{
		$this->interface->blockAddress($address, $timeout);
	}

	public function unblockAddress(string $address) : void{
		$this->interface->unblockAddress($address);
	}

	public function onRawPacketReceive(string $address, int $port, string $payload) : void{
		$this->network->processRawPacket($this, $address, $port, $payload);
	}

	public function sendRawPacket(string $address, int $port, string $payload) : void{
		$this->interface->sendRaw($address, $port, $payload);
	}

	public function addRawPacketFilter(string $regex) : void{
		$this->interface->addRawPacketFilter($regex);
	}

	public function onPacketAck(int $sessionId, int $identifierACK) : void{

	}

	public function setName(string $name) : void{
		$info = $this->server->getQueryInformation();

		$this->interface->setName(implode(";",
			[
				"MCPE",
				rtrim(addcslashes($name, ";"), '\\'),
				ProtocolInfo::CURRENT_PROTOCOL,
				ProtocolInfo::MINECRAFT_VERSION_NETWORK,
				$info->getPlayerCount(),
				$info->getMaxPlayerCount(),
				$this->rakServerId,
				$this->server->getName(),
				TypeConverter::getInstance()->protocolGameModeName($this->server->getGamemode())
			]) . ";"
		);
	}

	public function setPortCheck(bool $name) : void{
		$this->interface->setPortCheck($name);
	}

	public function setPacketLimit(int $limit) : void{
		$this->interface->setPacketsPerTickLimit($limit);
	}

	public function onBandwidthStatsUpdate(int $bytesSentDiff, int $bytesReceivedDiff) : void{
		$this->network->getBandwidthTracker()->add($bytesSentDiff, $bytesReceivedDiff);
	}

	public function putPacket(int $sessionId, string $payload, bool $immediate = true) : void{
		if(isset($this->sessions[$sessionId])){
			$pk = new EncapsulatedPacket();
			$pk->buffer = self::MCPE_RAKNET_PACKET_ID . $payload;
			$pk->reliability = PacketReliability::RELIABLE_ORDERED;
			$pk->orderChannel = 0;

			$this->interface->sendEncapsulated($sessionId, $pk, $immediate);
		}
	}

	public function onPingMeasure(int $sessionId, int $pingMS) : void{
		if(isset($this->sessions[$sessionId])){
			$this->sessions[$sessionId]->updatePing($pingMS);
		}
	}
}
