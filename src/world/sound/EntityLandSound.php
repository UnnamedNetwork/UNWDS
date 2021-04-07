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

namespace pocketmine\world\sound;

use pocketmine\block\Block;
use pocketmine\entity\Entity;
use pocketmine\math\Vector3;
use pocketmine\network\mcpe\convert\RuntimeBlockMapping;
use pocketmine\network\mcpe\protocol\LevelSoundEventPacket;

/**
 * Played when an entity hits the ground after falling a distance that doesn't cause damage, e.g. due to jumping.
 */
class EntityLandSound implements Sound{

	/** @var Entity */
	private $entity;
	/** @var Block */
	private $blockLandedOn;

	public function __construct(Entity $entity, Block $blockLandedOn){
		$this->entity = $entity;
		$this->blockLandedOn = $blockLandedOn;
	}

	public function encode(?Vector3 $pos) : array{
		return [LevelSoundEventPacket::create(
			LevelSoundEventPacket::SOUND_LAND,
			$pos,
			RuntimeBlockMapping::getInstance()->toRuntimeId($this->blockLandedOn->getFullId()),
			$this->entity::getNetworkTypeId()
			//TODO: does isBaby have any relevance here?
		)];
	}
}
