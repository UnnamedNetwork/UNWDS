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

use pocketmine\block\utils\TreeType;
use pocketmine\item\Item;
use pocketmine\math\Vector3;
use pocketmine\player\Player;

class Wood extends Opaque{

	private TreeType $treeType;

	private bool $stripped;

	public function __construct(BlockIdentifier $idInfo, string $name, BlockBreakInfo $breakInfo, TreeType $treeType, bool $stripped){
		$this->stripped = $stripped; //TODO: this should be dynamic, but right now legacy shit gets in the way
		parent::__construct($idInfo, $name, $breakInfo);
		$this->treeType = $treeType;
	}

	/**
	 * TODO: this is ad hoc, but add an interface for this to all tree-related blocks
	 */
	public function getTreeType() : TreeType{
		return $this->treeType;
	}

	public function isStripped() : bool{ return $this->stripped; }

	public function getFuelTime() : int{
		return 300;
	}

	public function getFlameEncouragement() : int{
		return 5;
	}

	public function getFlammability() : int{
		return 5;
	}

	public function onInteract(Item $item, int $face, Vector3 $clickVector, ?Player $player = null) : bool{
		if(!$this->stripped && ($item->getBlockToolType() & BlockToolType::AXE) !== 0){
			//TODO: strip logs; can't implement this yet because of legacy limitations :(
			return true;
		}
		return false;
	}
}
