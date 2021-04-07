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

namespace pocketmine\item;

use pocketmine\block\Block;
use pocketmine\block\tile\Banner as TileBanner;
use pocketmine\block\utils\BannerPattern;
use pocketmine\block\utils\DyeColor;
use pocketmine\data\bedrock\DyeColorIdMap;
use pocketmine\nbt\tag\CompoundTag;
use pocketmine\nbt\tag\ListTag;
use function count;

class Banner extends ItemBlockWallOrFloor{
	public const TAG_PATTERNS = TileBanner::TAG_PATTERNS;
	public const TAG_PATTERN_COLOR = TileBanner::TAG_PATTERN_COLOR;
	public const TAG_PATTERN_NAME = TileBanner::TAG_PATTERN_NAME;

	/** @var DyeColor */
	private $color;

	/**
	 * @var BannerPattern[]
	 * @phpstan-var list<BannerPattern>
	 */
	private $patterns = [];

	public function __construct(ItemIdentifier $identifier, Block $floorVariant, Block $wallVariant){
		parent::__construct($identifier, $floorVariant, $wallVariant);
		$this->color = DyeColor::BLACK();
	}

	public function getColor() : DyeColor{
		return $this->color;
	}

	/** @return $this */
	public function setColor(DyeColor $color) : self{
		$this->color = $color;
		return $this;
	}

	public function getMeta() : int{
		return DyeColorIdMap::getInstance()->toInvertedId($this->color);
	}

	public function getMaxStackSize() : int{
		return 16;
	}

	/**
	 * @return BannerPattern[]
	 * @phpstan-return list<BannerPattern>
	 */
	public function getPatterns() : array{
		return $this->patterns;
	}

	/**
	 * @param BannerPattern[] $patterns
	 * @phpstan-param list<BannerPattern> $patterns
	 *
	 * @return $this
	 */
	public function setPatterns(array $patterns) : self{
		$this->patterns = $patterns;
		return $this;
	}

	public function getFuelTime() : int{
		return 300;
	}

	protected function deserializeCompoundTag(CompoundTag $tag) : void{
		parent::deserializeCompoundTag($tag);

		$this->patterns = [];

		$colorIdMap = DyeColorIdMap::getInstance();
		$patterns = $tag->getListTag(self::TAG_PATTERNS);
		if($patterns !== null){
			/** @var CompoundTag $t */
			foreach($patterns as $t){
				$this->patterns[] = new BannerPattern($t->getString(self::TAG_PATTERN_NAME), $colorIdMap->fromInvertedId($t->getInt(self::TAG_PATTERN_COLOR)));
			}
		}
	}

	protected function serializeCompoundTag(CompoundTag $tag) : void{
		parent::serializeCompoundTag($tag);

		if(count($this->patterns) > 0){
			$patterns = new ListTag();
			$colorIdMap = DyeColorIdMap::getInstance();
			foreach($this->patterns as $pattern){
				$patterns->push(CompoundTag::create()
					->setString(self::TAG_PATTERN_NAME, $pattern->getId())
					->setInt(self::TAG_PATTERN_COLOR, $colorIdMap->toInvertedId($pattern->getColor()))
				);
			}

			$tag->setTag(self::TAG_PATTERNS, $patterns);
		}else{
			$tag->removeTag(self::TAG_PATTERNS);
		}
	}
}
