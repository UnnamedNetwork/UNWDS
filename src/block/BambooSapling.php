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

use pocketmine\item\Bamboo as ItemBamboo;
use pocketmine\item\Fertilizer;
use pocketmine\item\Item;
use pocketmine\math\Vector3;
use pocketmine\player\Player;
use pocketmine\world\BlockTransaction;

final class BambooSapling extends Flowable{

	/** @var bool */
	private $ready = false;

	public function readStateFromData(int $id, int $stateMeta) : void{
		$this->ready = ($stateMeta & BlockLegacyMetadata::SAPLING_FLAG_READY) !== 0;
	}

	protected function writeStateToMeta() : int{
		return $this->ready ? BlockLegacyMetadata::SAPLING_FLAG_READY : 0;
	}

	public function getStateBitmask() : int{ return 0b1000; }

	public function isReady() : bool{ return $this->ready; }

	/** @return $this */
	public function setReady(bool $ready) : self{
		$this->ready = $ready;
		return $this;
	}

	private function canBeSupportedBy(Block $block) : bool{
		//TODO: tags would be better for this
		return
			$block instanceof Dirt ||
			$block instanceof Grass ||
			$block instanceof Gravel ||
			$block instanceof Sand ||
			$block instanceof Mycelium ||
			$block instanceof Podzol;
	}

	public function place(BlockTransaction $tx, Item $item, Block $blockReplace, Block $blockClicked, int $face, Vector3 $clickVector, ?Player $player = null) : bool{
		if(!$this->canBeSupportedBy($blockReplace->pos->getWorld()->getBlock($blockReplace->pos->down()))){
			return false;
		}
		return parent::place($tx, $item, $blockReplace, $blockClicked, $face, $clickVector, $player);
	}

	public function onInteract(Item $item, int $face, Vector3 $clickVector, ?Player $player = null) : bool{
		if($item instanceof Fertilizer || $item instanceof ItemBamboo){
			if($this->grow()){
				$item->pop();
				return true;
			}
		}
		return false;
	}

	public function onNearbyBlockChange() : void{
		if(!$this->canBeSupportedBy($this->pos->getWorld()->getBlock($this->pos->down()))){
			$this->pos->getWorld()->useBreakOn($this->pos);
		}
	}

	private function grow() : bool{
		$world = $this->pos->getWorld();
		if(!$world->getBlock($this->pos->up())->canBeReplaced()){
			return false;
		}

		$tx = new BlockTransaction($world);
		$bamboo = VanillaBlocks::BAMBOO();
		$tx->addBlock($this->pos, $bamboo)
			->addBlock($this->pos->up(), (clone $bamboo)->setLeafSize(Bamboo::SMALL_LEAVES));
		return $tx->apply();
	}

	public function ticksRandomly() : bool{
		return true;
	}

	public function onRandomTick() : void{
		$world = $this->pos->getWorld();
		if($this->ready){
			$this->ready = false;
			if($world->getFullLight($this->pos) < 9 || !$this->grow()){
				$world->setBlock($this->pos, $this);
			}
		}elseif($world->getBlock($this->pos->up())->canBeReplaced()){
			$this->ready = true;
			$world->setBlock($this->pos, $this);
		}
	}

	public function asItem() : Item{
		return VanillaBlocks::BAMBOO()->asItem();
	}
}
