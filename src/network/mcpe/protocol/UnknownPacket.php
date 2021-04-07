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

namespace pocketmine\network\mcpe\protocol;

use pocketmine\network\mcpe\protocol\serializer\PacketSerializer;
use function ord;
use function strlen;

class UnknownPacket extends DataPacket{
	public const NETWORK_ID = -1; //Invalid, do not try to write this

	/** @var string */
	public $payload;

	public function pid() : int{
		if(strlen($this->payload ?? "") > 0){
			return ord($this->payload[0]);
		}
		return self::NETWORK_ID;
	}

	public function getName() : string{
		return "unknown packet";
	}

	protected function decodeHeader(PacketSerializer $in) : void{

	}

	protected function decodePayload(PacketSerializer $in) : void{
		$this->payload = $in->getRemaining();
	}

	protected function encodeHeader(PacketSerializer $out) : void{

	}

	protected function encodePayload(PacketSerializer $out) : void{
		$out->put($this->payload);
	}

	public function handle(PacketHandlerInterface $handler) : bool{
		return false;
	}
}
