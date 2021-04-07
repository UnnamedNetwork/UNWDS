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

use pocketmine\block\tile\Skull as TileSkull;
use pocketmine\block\utils\BlockDataSerializer;
use pocketmine\block\utils\SkullType;
use pocketmine\item\Item;
use pocketmine\item\ItemFactory;
use pocketmine\item\ItemIds;
use pocketmine\item\Skull as ItemSkull;
use pocketmine\math\AxisAlignedBB;
use pocketmine\math\Facing;
use pocketmine\math\Vector3;
use pocketmine\player\Player;
use pocketmine\world\BlockTransaction;
use function assert;
use function floor;

class Skull extends Flowable{
	/** @var SkullType */
	protected $skullType;

	/** @var int */
	protected $facing = Facing::NORTH;

	/** @var int */
	protected $rotation = 0; //TODO: split this into floor skull and wall skull handling

	public function __construct(BlockIdentifier $idInfo, string $name, ?BlockBreakInfo $breakInfo = null){
		$this->skullType = SkullType::SKELETON(); //TODO: this should be a parameter
		parent::__construct($idInfo, $name, $breakInfo ?? new BlockBreakInfo(1.0));
	}

	protected function writeStateToMeta() : int{
		return $this->facing === Facing::UP ? 1 : BlockDataSerializer::writeHorizontalFacing($this->facing);
	}

	public function readStateFromData(int $id, int $stateMeta) : void{
		$this->facing = $stateMeta === 1 ? Facing::UP : BlockDataSerializer::readHorizontalFacing($stateMeta);
	}

	public function getStateBitmask() : int{
		return 0b111;
	}

	public function readStateFromWorld() : void{
		parent::readStateFromWorld();
		$tile = $this->pos->getWorld()->getTile($this->pos);
		if($tile instanceof TileSkull){
			$this->skullType = $tile->getSkullType();
			$this->rotation = $tile->getRotation();
		}
	}

	public function writeStateToWorld() : void{
		parent::writeStateToWorld();
		//extra block properties storage hack
		$tile = $this->pos->getWorld()->getTile($this->pos);
		assert($tile instanceof TileSkull);
		$tile->setRotation($this->rotation);
		$tile->setSkullType($this->skullType);
	}

	public function getSkullType() : SkullType{
		return $this->skullType;
	}

	/**
	 * @return AxisAlignedBB[]
	 */
	protected function recalculateCollisionBoxes() : array{
		//TODO: different bounds depending on attached face
		return [AxisAlignedBB::one()->contract(0.25, 0, 0.25)->trim(Facing::UP, 0.5)];
	}

	public function place(BlockTransaction $tx, Item $item, Block $blockReplace, Block $blockClicked, int $face, Vector3 $clickVector, ?Player $player = null) : bool{
		if($face === Facing::DOWN){
			return false;
		}

		$this->facing = $face;
		if($item instanceof ItemSkull){
			$this->skullType = $item->getSkullType(); //TODO: the item should handle this, but this hack is currently needed because of tile mess
		}
		if($player !== null and $face === Facing::UP){
			$this->rotation = ((int) floor(($player->getLocation()->getYaw() * 16 / 360) + 0.5)) & 0xf;
		}
		return parent::place($tx, $item, $blockReplace, $blockClicked, $face, $clickVector, $player);
	}

	public function asItem() : Item{
		return ItemFactory::getInstance()->get(ItemIds::SKULL, $this->skullType->getMagicNumber());
	}
}
