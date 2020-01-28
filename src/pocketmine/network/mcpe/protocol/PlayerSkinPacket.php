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

use pocketmine\entity\Skin;
use pocketmine\network\mcpe\NetworkSession;
use pocketmine\utils\SerializedImage;
use pocketmine\utils\UUID;

class PlayerSkinPacket extends DataPacket{
	public const NETWORK_ID = ProtocolInfo::PLAYER_SKIN_PACKET;

	/** @var UUID */
	public $uuid;
	/** @var string */
	public $oldSkinName = "";
	/** @var string */
	public $newSkinName = "";
	/** @var Skin */
	public $skin;

	protected function decodePayload(){
		$this->uuid = $this->getUUID();
		if($this->protocol >= ProtocolInfo::PROTOCOL_1_13) {
            $this->skin = $this->getSkin(); // 1.13
        }
		else {
		    $skin = new Skin();

		    $skin->setSkinId($this->getString());
		    $this->oldSkinName = $this->getString();
		    $this->newSkinName = $this->getString();
		    $skin->setSkinData(SerializedImage::fromLegacy($this->getString()));
		    $skin->setCapeData(SerializedImage::fromLegacy($this->getString()));
		    $skin->setSkinResourcePatch(Skin::DEFAULT_SKIN_RESOURCE_PATCH); // $this->>getString(); geometry name
		    $skin->setGeometryData(""); // $this->getString(); ->geometry data
		    $this->skin = $skin;
        }
	}

	protected function encodePayload(){
		$this->putUUID($this->uuid);
		if($this->protocol >= ProtocolInfo::PROTOCOL_1_13) {
            $this->putSkin($this->skin); // 1.13+
        }
		else {
		    if($this->skin->version > ProtocolInfo::PROTOCOL_1_12) {
		        $this->skin = Skin::getRandomSkin();
            }

		    $this->putString($this->skin->getSkinId());
		    $this->putString($this->oldSkinName);
		    $this->putString($this->newSkinName);
		    $this->putString($this->skin->getSkinData()->data);
		    $this->putString($this->skin->getCapeData()->data);
		    $this->putString($this->skin->getSkinGeometryName());
		    $this->putString(Skin::prepareGeometryDataForOld($this->skin->getGeometryData()));
        }
	}

	public function handle(NetworkSession $session) : bool{
		return $session->handlePlayerSkin($this);
	}
}
