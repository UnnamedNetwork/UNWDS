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

use pocketmine\network\mcpe\protocol\serializer\PacketSerializer;

class MobEffectPacket extends DataPacket implements ClientboundPacket{
	public const NETWORK_ID = ProtocolInfo::MOB_EFFECT_PACKET;

	public const EVENT_ADD = 1;
	public const EVENT_MODIFY = 2;
	public const EVENT_REMOVE = 3;

	/** @var int */
	public $entityRuntimeId;
	/** @var int */
	public $eventId;
	/** @var int */
	public $effectId;
	/** @var int */
	public $amplifier = 0;
	/** @var bool */
	public $particles = true;
	/** @var int */
	public $duration = 0;

	public static function add(int $entityRuntimeId, bool $replace, int $effectId, int $amplifier, bool $particles, int $duration) : self{
		$result = new self;
		$result->eventId = $replace ? self::EVENT_MODIFY : self::EVENT_ADD;
		$result->entityRuntimeId = $entityRuntimeId;
		$result->effectId = $effectId;
		$result->amplifier = $amplifier;
		$result->particles = $particles;
		$result->duration = $duration;
		return $result;
	}

	public static function remove(int $entityRuntimeId, int $effectId) : self{
		$pk = new self;
		$pk->eventId = self::EVENT_REMOVE;
		$pk->entityRuntimeId = $entityRuntimeId;
		$pk->effectId = $effectId;
		return $pk;
	}

	protected function decodePayload(PacketSerializer $in) : void{
		$this->entityRuntimeId = $in->getEntityRuntimeId();
		$this->eventId = $in->getByte();
		$this->effectId = $in->getVarInt();
		$this->amplifier = $in->getVarInt();
		$this->particles = $in->getBool();
		$this->duration = $in->getVarInt();
	}

	protected function encodePayload(PacketSerializer $out) : void{
		$out->putEntityRuntimeId($this->entityRuntimeId);
		$out->putByte($this->eventId);
		$out->putVarInt($this->effectId);
		$out->putVarInt($this->amplifier);
		$out->putBool($this->particles);
		$out->putVarInt($this->duration);
	}

	public function handle(PacketHandlerInterface $handler) : bool{
		return $handler->handleMobEffect($this);
	}
}
