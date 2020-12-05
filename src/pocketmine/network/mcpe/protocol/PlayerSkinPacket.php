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

use pocketmine\network\mcpe\NetworkSession;
use pocketmine\network\mcpe\protocol\types\SkinData;
use pocketmine\utils\UUID;

class PlayerSkinPacket extends DataPacket{
	public const NETWORK_ID = ProtocolInfo::PLAYER_SKIN_PACKET;

	/** @var UUID */
	public $uuid;
	/** @var string */
	public $oldSkinName = "";
	/** @var string */
	public $newSkinName = "";
	/** @var SkinData */
	public $skin;

	protected function decodePayload(){
		$this->uuid = $this->getUUID();
		$this->skin = $this->getSkin();
		$this->newSkinName = $this->getString();
		$this->oldSkinName = $this->getString();
		$this->skin->setVerified($this->getBool());
	}

	protected function encodePayload(){
		$this->putUUID($this->uuid);
		$this->putSkin($this->skin);
		$this->putString($this->newSkinName);
		$this->putString($this->oldSkinName);
		$this->putBool($this->skin->isVerified());
	}

	public function handle(NetworkSession $session) : bool{
		return $session->handlePlayerSkin($this);
	}
}
