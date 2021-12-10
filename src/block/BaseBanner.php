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

use pocketmine\block\tile\Banner as TileBanner;
use pocketmine\block\utils\BannerPatternLayer;
use pocketmine\block\utils\ColoredTrait;
use pocketmine\block\utils\DyeColor;
use pocketmine\data\bedrock\DyeColorIdMap;
use pocketmine\item\Banner as ItemBanner;
use pocketmine\item\Item;
use pocketmine\math\AxisAlignedBB;
use pocketmine\math\Vector3;
use pocketmine\player\Player;
use pocketmine\world\BlockTransaction;
use function array_filter;
use function assert;
use function count;

abstract class BaseBanner extends Transparent{
	use ColoredTrait;

	/**
	 * @var BannerPatternLayer[]
	 * @phpstan-var list<BannerPatternLayer>
	 */
	protected array $patterns = [];

	public function __construct(BlockIdentifier $idInfo, string $name, BlockBreakInfo $breakInfo){
		$this->color = DyeColor::BLACK();
		parent::__construct($idInfo, $name, $breakInfo);
	}

	public function readStateFromWorld() : void{
		parent::readStateFromWorld();
		$tile = $this->position->getWorld()->getTile($this->position);
		if($tile instanceof TileBanner){
			$this->color = $tile->getBaseColor();
			$this->setPatterns($tile->getPatterns());
		}
	}

	public function writeStateToWorld() : void{
		parent::writeStateToWorld();
		$tile = $this->position->getWorld()->getTile($this->position);
		assert($tile instanceof TileBanner);
		$tile->setBaseColor($this->color);
		$tile->setPatterns($this->patterns);
	}

	public function isSolid() : bool{
		return false;
	}

	public function getMaxStackSize() : int{
		return 16;
	}

	/**
	 * @return BannerPatternLayer[]
	 * @phpstan-return list<BannerPatternLayer>
	 */
	public function getPatterns() : array{
		return $this->patterns;
	}

	/**
	 * @param BannerPatternLayer[]             $patterns
	 *
	 * @phpstan-param list<BannerPatternLayer> $patterns
	 * @return $this
	 */
	public function setPatterns(array $patterns) : self{
		$checked = array_filter($patterns, fn($v) => $v instanceof BannerPatternLayer);
		if(count($checked) !== count($patterns)){
			throw new \TypeError("Deque must only contain " . BannerPatternLayer::class . " objects");
		}
		$this->patterns = $checked;
		return $this;
	}

	/**
	 * @return AxisAlignedBB[]
	 */
	protected function recalculateCollisionBoxes() : array{
		return [];
	}

	public function place(BlockTransaction $tx, Item $item, Block $blockReplace, Block $blockClicked, int $face, Vector3 $clickVector, ?Player $player = null) : bool{
		if($item instanceof ItemBanner){
			$this->color = $item->getColor();
			$this->setPatterns($item->getPatterns());
		}

		return parent::place($tx, $item, $blockReplace, $blockClicked, $face, $clickVector, $player);
	}

	abstract protected function getSupportingFace() : int;

	public function onNearbyBlockChange() : void{
		if($this->getSide($this->getSupportingFace())->getId() === BlockLegacyIds::AIR){
			$this->position->getWorld()->useBreakOn($this->position);
		}
	}

	protected function writeStateToItemMeta() : int{
		return DyeColorIdMap::getInstance()->toInvertedId($this->color);
	}

	public function getDropsForCompatibleTool(Item $item) : array{
		$drop = $this->asItem();
		if($drop instanceof ItemBanner and count($this->patterns) > 0){
			$drop->setPatterns($this->patterns);
		}

		return [$drop];
	}

	public function getPickedItem(bool $addUserData = false) : Item{
		$result = $this->asItem();
		if($addUserData and $result instanceof ItemBanner and count($this->patterns) > 0){
			$result->setPatterns($this->patterns);
		}
		return $result;
	}
}
