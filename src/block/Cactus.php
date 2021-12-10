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

namespace pocketmine\block;

use pocketmine\block\utils\BlockDataSerializer;
use pocketmine\entity\Entity;
use pocketmine\event\block\BlockGrowEvent;
use pocketmine\event\entity\EntityDamageByBlockEvent;
use pocketmine\event\entity\EntityDamageEvent;
use pocketmine\item\Item;
use pocketmine\math\AxisAlignedBB;
use pocketmine\math\Facing;
use pocketmine\math\Vector3;
use pocketmine\player\Player;
use pocketmine\world\BlockTransaction;

class Cactus extends Transparent{

	protected int $age = 0;

	protected function writeStateToMeta() : int{
		return $this->age;
	}

	public function readStateFromData(int $id, int $stateMeta) : void{
		$this->age = BlockDataSerializer::readBoundedInt("age", $stateMeta, 0, 15);
	}

	public function getStateBitmask() : int{
		return 0b1111;
	}

	public function getAge() : int{ return $this->age; }

	/** @return $this */
	public function setAge(int $age) : self{
		if($age < 0 || $age > 15){
			throw new \InvalidArgumentException("Age must be in range 0-15");
		}
		$this->age = $age;
		return $this;
	}

	public function hasEntityCollision() : bool{
		return true;
	}

	/**
	 * @return AxisAlignedBB[]
	 */
	protected function recalculateCollisionBoxes() : array{
		static $shrinkSize = 1 / 16;
		return [AxisAlignedBB::one()->contract($shrinkSize, 0, $shrinkSize)->trim(Facing::UP, $shrinkSize)];
	}

	public function onEntityInside(Entity $entity) : bool{
		$ev = new EntityDamageByBlockEvent($this, $entity, EntityDamageEvent::CAUSE_CONTACT, 1);
		$entity->attack($ev);
		return true;
	}

	public function onNearbyBlockChange() : void{
		$down = $this->getSide(Facing::DOWN);
		if($down->getId() !== BlockLegacyIds::SAND and !$down->isSameType($this)){
			$this->position->getWorld()->useBreakOn($this->position);
		}else{
			foreach(Facing::HORIZONTAL as $side){
				$b = $this->getSide($side);
				if($b->isSolid()){
					$this->position->getWorld()->useBreakOn($this->position);
					break;
				}
			}
		}
	}

	public function ticksRandomly() : bool{
		return true;
	}

	public function onRandomTick() : void{
		if(!$this->getSide(Facing::DOWN)->isSameType($this)){
			if($this->age === 15){
				for($y = 1; $y < 3; ++$y){
					if(!$this->position->getWorld()->isInWorld($this->position->x, $this->position->y + $y, $this->position->z)){
						break;
					}
					$b = $this->position->getWorld()->getBlockAt($this->position->x, $this->position->y + $y, $this->position->z);
					if($b->getId() === BlockLegacyIds::AIR){
						$ev = new BlockGrowEvent($b, VanillaBlocks::CACTUS());
						$ev->call();
						if($ev->isCancelled()){
							break;
						}
						$this->position->getWorld()->setBlock($b->position, $ev->getNewState());
					}else{
						break;
					}
				}
				$this->age = 0;
				$this->position->getWorld()->setBlock($this->position, $this);
			}else{
				++$this->age;
				$this->position->getWorld()->setBlock($this->position, $this);
			}
		}
	}

	public function place(BlockTransaction $tx, Item $item, Block $blockReplace, Block $blockClicked, int $face, Vector3 $clickVector, ?Player $player = null) : bool{
		$down = $this->getSide(Facing::DOWN);
		if($down->getId() === BlockLegacyIds::SAND or $down->isSameType($this)){
			foreach(Facing::HORIZONTAL as $side){
				if($this->getSide($side)->isSolid()){
					return false;
				}
			}

			return parent::place($tx, $item, $blockReplace, $blockClicked, $face, $clickVector, $player);
		}

		return false;
	}
}
