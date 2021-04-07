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

use pocketmine\event\block\BlockSpreadEvent;
use pocketmine\item\Item;
use pocketmine\math\Facing;
use function mt_rand;

class Mycelium extends Opaque{

	public function __construct(BlockIdentifier $idInfo, string $name, ?BlockBreakInfo $breakInfo = null){
		parent::__construct($idInfo, $name, $breakInfo ?? new BlockBreakInfo(0.6, BlockToolType::SHOVEL));
	}

	public function getDropsForCompatibleTool(Item $item) : array{
		return [
			VanillaBlocks::DIRT()->asItem()
		];
	}

	public function isAffectedBySilkTouch() : bool{
		return true;
	}

	public function ticksRandomly() : bool{
		return true;
	}

	public function onRandomTick() : void{
		//TODO: light levels
		$x = mt_rand($this->pos->x - 1, $this->pos->x + 1);
		$y = mt_rand($this->pos->y - 2, $this->pos->y + 2);
		$z = mt_rand($this->pos->z - 1, $this->pos->z + 1);
		$block = $this->pos->getWorld()->getBlockAt($x, $y, $z);
		if($block->getId() === BlockLegacyIds::DIRT){
			if($block->getSide(Facing::UP) instanceof Transparent){
				$ev = new BlockSpreadEvent($block, $this, VanillaBlocks::MYCELIUM());
				$ev->call();
				if(!$ev->isCancelled()){
					$this->pos->getWorld()->setBlock($block->pos, $ev->getNewState());
				}
			}
		}
	}
}
