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

#include <rules/DataPacket.h>


use pocketmine\entity\data\SkinAnimation;
use pocketmine\entity\Skin;
use pocketmine\network\mcpe\NetworkSession;
use pocketmine\utils\BinaryStream;
use pocketmine\utils\MainLogger;
use pocketmine\utils\SerializedImage;
use pocketmine\utils\Utils;
use function get_class;
use function json_decode;
use const pocketmine\RESOURCE_PATH;

class LoginPacket extends DataPacket{
	public const NETWORK_ID = ProtocolInfo::LOGIN_PACKET;

	public const EDITION_POCKET = 0;

	/** @var string */
	public $username;
	/** @var int */
	public $protocol;
	/** @var string */
	public $clientUUID;
	/** @var int */
	public $clientId;
	/** @var string */
	public $xuid;
	/** @var string */
	public $identityPublicKey;
	/** @var string */
	public $serverAddress;
	/** @var string */
	public $locale;
	/** @var Skin $skin */
	public $skin;

	/** @var array (the "chain" index contains one or more JWTs) */
	public $chainData = [];
	/** @var string */
	public $clientDataJwt;
	/** @var array decoded payload of the clientData JWT */
	public $clientData = [];

	/**
	 * This field may be used by plugins to bypass keychain verification. It should only be used for plugins such as
	 * Specter where passing verification would take too much time and not be worth it.
	 *
	 * @var bool
	 */
	public $skipVerification = false;

	public function canBeSentBeforeLogin() : bool{
		return true;
	}

	public function mayHaveUnreadBytes() : bool{
		return $this->protocol !== null and $this->protocol !== ProtocolInfo::CURRENT_PROTOCOL;
	}

	protected function decodePayload(){
		$this->protocol = $this->getInt();

		try{
			$this->decodeConnectionRequest();
			$this->decodeSkin();
		}catch(\Throwable $e){
			if($this->protocol === ProtocolInfo::CURRENT_PROTOCOL){
				throw $e;
			}

			$logger = MainLogger::getLogger();
			$logger->debug(get_class($e) . " was thrown while decoding connection request in login (protocol version " . ($this->protocol ?? "unknown") . "): " . $e->getMessage());
			foreach(Utils::printableTrace($e->getTrace()) as $line){
				$logger->debug($line);
			}
		}
	}

	protected function decodeConnectionRequest() : void{
		$buffer = new BinaryStream($this->getString());

		$this->chainData = json_decode($buffer->get($buffer->getLInt()), true);

		$hasExtraData = false;
		foreach($this->chainData["chain"] as $chain){
			$webtoken = Utils::decodeJWT($chain);
			if(isset($webtoken["extraData"])){
				if($hasExtraData){
					throw new \RuntimeException("Found 'extraData' multiple times in key chain");
				}
				$hasExtraData = true;
				if(isset($webtoken["extraData"]["displayName"])){
					$this->username = $webtoken["extraData"]["displayName"];
				}
				if(isset($webtoken["extraData"]["identity"])){
					$this->clientUUID = $webtoken["extraData"]["identity"];
				}
				if(isset($webtoken["extraData"]["XUID"])){
					$this->xuid = $webtoken["extraData"]["XUID"];
				}
			}

			if(isset($webtoken["identityPublicKey"])){
				$this->identityPublicKey = $webtoken["identityPublicKey"];
			}
		}

		$this->clientDataJwt = $buffer->get($buffer->getLInt());
		$this->clientData = Utils::decodeJWT($this->clientDataJwt);

		$this->clientId = $this->clientData["ClientRandomId"] ?? null;
		$this->serverAddress = $this->clientData["ServerAddress"] ?? null;

		$this->locale = $this->clientData["LanguageCode"] ?? null;
	}

	protected function decodeSkin() {
        $skin = new Skin();

        $skinToken = $this->clientData;

        if(isset($skinToken["SkinId"])) {
            $skin->setSkinId((string)$skinToken["SkinId"]);
        }
        if(isset($skinToken["CapeId"])) {
            $skin->setCapeId((string)$skinToken["CapeId"]);
        }

        $skin->setSkinData($this->decodeImage($skinToken, "Skin"));
        $skin->setCapeData($this->decodeImage($skinToken, "Cape"));

        $premium = false;
        $persona = false;
        $capeOnClassic = false;

        if(isset($skinToken["PremiumSkin"])) {
            $premium = (bool)$skinToken["PremiumSkin"];
        }

        if(isset($skinToken["PersonaSkin"])) {
            $persona = (bool)$skinToken["PersonaSkin"];
        }

        if(isset($skinToken["CapeOnClassicSkin"])) {
            $capeOnClassic = (bool)$skinToken["CapeOnClassicSkin"];
        }

        $skin->setPremium($premium);
        $skin->setPersona($persona);
        $skin->setCapeOnClassic($capeOnClassic);

        $skin->version = $this->protocol > ProtocolInfo::PROTOCOL_1_12 ? ProtocolInfo::PROTOCOL_1_13 : ProtocolInfo::PROTOCOL_1_12;

        $skinResourcePatch = Skin::getGeometryCustomConstant();
        $skinGeometryData = "";

        // 1.13+
        if(isset($skinToken["SkinResourcePatch"])) {
            $skinResourcePatch = base64_decode($skinToken["SkinResourcePatch"]);
        }

        if(isset($skinToken["SkinGeometryData"])) {
            $skinGeometryData = base64_decode($skinToken["SkinGeometryData"]);
        }

        // 1.12
        /*if(isset($skinToken["SkinGeometryName"])) {
            $skinResourcePatch = str_replace(Skin::DEFAULT_SKIN_GEOMETRY_NAME, $skinToken["SkinGeometryName"], Skin::DEFAULT_SKIN_RESOURCE_PATCH);
        }

        if(isset($skinToken["SkinGeometry"])) {
            $skinGeometryData = base64_decode($skinToken["SkinGeometry"]);
        }*/

        $skin->setSkinResourcePatch($skinResourcePatch);
        $skin->setGeometryData($skinGeometryData);

        if(isset($skinToken["AnimationData"])) {
            $skin->setAnimationData(base64_decode($skinToken["AnimationData"]));
        }

        if(isset($skinToken["AnimatedImageData"])) {
            foreach ($skinToken["AnimatedImageData"] as $tokens) {
                $skin->animations[] = $this->decodeAnimation($tokens);
            }
        }

        $this->skin = $skin;
        // TODO: Animated image data
    }

    /**
     * @param array $data
     * @return SkinAnimation
     */
    protected function decodeAnimation(array $data): SkinAnimation {
        return new SkinAnimation(new SerializedImage($data["ImageWidth"], $data["ImageHeight"], (string)base64_decode($data["Image"])), (int)$data["Type"], (float)$data["Frames"]);
    }

    /**
     * @param array $token
     * @param string $name
     *
     * @return SerializedImage
     */
    protected function decodeImage(array $token, string $name) {
        $index = $name . "Data";
        if(isset($token[$index])) {
            $skinImage = base64_decode($token[$index]);
            if(!$skinImage) {
                return SerializedImage::createEmpty();
            }

            if(isset($token[$name . "ImageHeight"]) && isset($token[$name . "ImageWidth"])) {
                return new SerializedImage((int)$token[$name . "ImageWidth"], (int)$token[$name. "ImageHeight"], $skinImage);
            }

            return SerializedImage::fromLegacy($skinImage);
        }

        return SerializedImage::createEmpty();
    }

	protected function encodePayload(){
		//TODO
	}

	public function handle(NetworkSession $session) : bool{
		return $session->handleLogin($this);
	}
}
