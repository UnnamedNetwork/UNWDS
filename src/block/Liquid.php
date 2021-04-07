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
use pocketmine\event\block\BlockFormEvent;
use pocketmine\event\block\BlockSpreadEvent;
use pocketmine\item\Item;
use pocketmine\math\AxisAlignedBB;
use pocketmine\math\Vector3;
use pocketmine\world\sound\FizzSound;
use pocketmine\world\sound\Sound;
use pocketmine\world\World;
use function array_fill;
use function intdiv;
use function lcg_value;
use function min;

abstract class Liquid extends Transparent{
	/** @var BlockIdentifierFlattened */
	protected $idInfo;

	/** @var int */
	public $adjacentSources = 0;

	/** @var Vector3|null */
	protected $flowVector = null;

	/** @var int[] */
	private $flowCostVisited = [];

	private const CAN_FLOW_DOWN = 1;
	private const CAN_FLOW = 0;
	private const BLOCKED = -1;

	/** @var bool */
	protected $falling = false;
	/** @var int */
	protected $decay = 0; //PC "level" property
	/** @var bool */
	protected $still = false;

	public function __construct(BlockIdentifierFlattened $idInfo, string $name, ?BlockBreakInfo $breakInfo = null){
		parent::__construct($idInfo, $name, $breakInfo ?? BlockBreakInfo::indestructible(500.0));
	}

	public function getId() : int{
		return $this->still ? $this->idInfo->getSecondId() : parent::getId();
	}

	protected function writeStateToMeta() : int{
		return $this->decay | ($this->falling ? BlockLegacyMetadata::LIQUID_FLAG_FALLING : 0);
	}

	public function readStateFromData(int $id, int $stateMeta) : void{
		$this->decay = BlockDataSerializer::readBoundedInt("decay", $stateMeta & 0x07, 0, 7);
		$this->falling = ($stateMeta & BlockLegacyMetadata::LIQUID_FLAG_FALLING) !== 0;
		$this->still = $id === $this->idInfo->getSecondId();
	}

	public function getStateBitmask() : int{
		return 0b1111;
	}

	public function isFalling() : bool{ return $this->falling; }

	/** @return $this */
	public function setFalling(bool $falling) : self{
		$this->falling = $falling;
		return $this;
	}

	public function getDecay() : int{ return $this->decay; }

	/** @return $this */
	public function setDecay(int $decay) : self{
		$this->decay = $decay;
		return $this;
	}

	public function hasEntityCollision() : bool{
		return true;
	}

	public function canBeReplaced() : bool{
		return true;
	}

	public function canBeFlowedInto() : bool{
		return true;
	}

	public function isSolid() : bool{
		return false;
	}

	/**
	 * @return AxisAlignedBB[]
	 */
	protected function recalculateCollisionBoxes() : array{
		return [];
	}

	public function getDropsForCompatibleTool(Item $item) : array{
		return [];
	}

	public function getStillForm() : Block{
		$b = clone $this;
		$b->still = true;
		return $b;
	}

	public function getFlowingForm() : Block{
		$b = clone $this;
		$b->still = false;
		return $b;
	}

	abstract public function getBucketFillSound() : Sound;

	abstract public function getBucketEmptySound() : Sound;

	public function isSource() : bool{
		return !$this->falling and $this->decay === 0;
	}

	/**
	 * @return float
	 */
	public function getFluidHeightPercent(){
		return (($this->falling ? 0 : $this->decay) + 1) / 9;
	}

	public function isStill() : bool{
		return $this->still;
	}

	/**
	 * @return $this
	 */
	public function setStill(bool $still = true) : self{
		$this->still = $still;
		return $this;
	}

	protected function getEffectiveFlowDecay(Block $block) : int{
		if(!($block instanceof Liquid) or !$block->isSameType($this)){
			return -1;
		}

		return $block->falling ? 0 : $block->decay;
	}

	public function readStateFromWorld() : void{
		parent::readStateFromWorld();
		$this->flowVector = null;
	}

	public function getFlowVector() : Vector3{
		if($this->flowVector !== null){
			return $this->flowVector;
		}

		$vX = $vY = $vZ = 0;

		$decay = $this->getEffectiveFlowDecay($this);

		$world = $this->pos->getWorld();

		for($j = 0; $j < 4; ++$j){

			$x = $this->pos->x;
			$y = $this->pos->y;
			$z = $this->pos->z;

			if($j === 0){
				--$x;
			}elseif($j === 1){
				++$x;
			}elseif($j === 2){
				--$z;
			}elseif($j === 3){
				++$z;
			}

			$sideBlock = $world->getBlockAt($x, $y, $z);
			$blockDecay = $this->getEffectiveFlowDecay($sideBlock);

			if($blockDecay < 0){
				if(!$sideBlock->canBeFlowedInto()){
					continue;
				}

				$blockDecay = $this->getEffectiveFlowDecay($world->getBlockAt($x, $y - 1, $z));

				if($blockDecay >= 0){
					$realDecay = $blockDecay - ($decay - 8);
					$vX += ($x - $this->pos->x) * $realDecay;
					$vY += ($y - $this->pos->y) * $realDecay;
					$vZ += ($z - $this->pos->z) * $realDecay;
				}

				continue;
			}else{
				$realDecay = $blockDecay - $decay;
				$vX += ($x - $this->pos->x) * $realDecay;
				$vY += ($y - $this->pos->y) * $realDecay;
				$vZ += ($z - $this->pos->z) * $realDecay;
			}
		}

		$vector = new Vector3($vX, $vY, $vZ);

		if($this->falling){
			if(
				!$this->canFlowInto($world->getBlockAt($this->pos->x, $this->pos->y, $this->pos->z - 1)) or
				!$this->canFlowInto($world->getBlockAt($this->pos->x, $this->pos->y, $this->pos->z + 1)) or
				!$this->canFlowInto($world->getBlockAt($this->pos->x - 1, $this->pos->y, $this->pos->z)) or
				!$this->canFlowInto($world->getBlockAt($this->pos->x + 1, $this->pos->y, $this->pos->z)) or
				!$this->canFlowInto($world->getBlockAt($this->pos->x, $this->pos->y + 1, $this->pos->z - 1)) or
				!$this->canFlowInto($world->getBlockAt($this->pos->x, $this->pos->y + 1, $this->pos->z + 1)) or
				!$this->canFlowInto($world->getBlockAt($this->pos->x - 1, $this->pos->y + 1, $this->pos->z)) or
				!$this->canFlowInto($world->getBlockAt($this->pos->x + 1, $this->pos->y + 1, $this->pos->z))
			){
				$vector = $vector->normalize()->add(0, -6, 0);
			}
		}

		return $this->flowVector = $vector->normalize();
	}

	public function addVelocityToEntity(Entity $entity) : ?Vector3{
		if($entity->canBeMovedByCurrents()){
			return $this->getFlowVector();
		}
		return null;
	}

	abstract public function tickRate() : int;

	/**
	 * Returns how many liquid levels are lost per block flowed horizontally. Affects how far the liquid can flow.
	 */
	public function getFlowDecayPerBlock() : int{
		return 1;
	}

	public function onNearbyBlockChange() : void{
		if(!$this->checkForHarden()){
			$this->pos->getWorld()->scheduleDelayedBlockUpdate($this->pos, $this->tickRate());
		}
	}

	public function onScheduledUpdate() : void{
		$multiplier = $this->getFlowDecayPerBlock();

		if(!$this->isSource()){
			$smallestFlowDecay = -100;
			$this->adjacentSources = 0;
			$smallestFlowDecay = $this->getSmallestFlowDecay($this->pos->getWorld()->getBlockAt($this->pos->x, $this->pos->y, $this->pos->z - 1), $smallestFlowDecay);
			$smallestFlowDecay = $this->getSmallestFlowDecay($this->pos->getWorld()->getBlockAt($this->pos->x, $this->pos->y, $this->pos->z + 1), $smallestFlowDecay);
			$smallestFlowDecay = $this->getSmallestFlowDecay($this->pos->getWorld()->getBlockAt($this->pos->x - 1, $this->pos->y, $this->pos->z), $smallestFlowDecay);
			$smallestFlowDecay = $this->getSmallestFlowDecay($this->pos->getWorld()->getBlockAt($this->pos->x + 1, $this->pos->y, $this->pos->z), $smallestFlowDecay);

			$newDecay = $smallestFlowDecay + $multiplier;
			$falling = false;

			if($newDecay >= 8 or $smallestFlowDecay < 0){
				$newDecay = -1;
			}

			if($this->getEffectiveFlowDecay($this->pos->getWorld()->getBlockAt($this->pos->x, $this->pos->y + 1, $this->pos->z)) >= 0){
				$falling = true;
			}

			if($this->adjacentSources >= 2 and $this instanceof Water){
				$bottomBlock = $this->pos->getWorld()->getBlockAt($this->pos->x, $this->pos->y - 1, $this->pos->z);
				if($bottomBlock->isSolid() or ($bottomBlock instanceof Water and $bottomBlock->isSource())){
					$newDecay = 0;
					$falling = false;
				}
			}

			if($falling !== $this->falling or (!$falling and $newDecay !== $this->decay)){
				if(!$falling and $newDecay < 0){
					$this->pos->getWorld()->setBlock($this->pos, VanillaBlocks::AIR());
					return;
				}

				$this->falling = $falling;
				$this->decay = $falling ? 0 : $newDecay;
				$this->pos->getWorld()->setBlock($this->pos, $this); //local block update will cause an update to be scheduled
			}
		}

		$bottomBlock = $this->pos->getWorld()->getBlockAt($this->pos->x, $this->pos->y - 1, $this->pos->z);

		$this->flowIntoBlock($bottomBlock, 0, true);

		if($this->isSource() or !$bottomBlock->canBeFlowedInto()){
			if($this->falling){
				$adjacentDecay = 1; //falling liquid behaves like source block
			}else{
				$adjacentDecay = $this->decay + $multiplier;
			}

			if($adjacentDecay < 8){
				$flags = $this->getOptimalFlowDirections();

				if($flags[0]){
					$this->flowIntoBlock($this->pos->getWorld()->getBlockAt($this->pos->x - 1, $this->pos->y, $this->pos->z), $adjacentDecay, false);
				}

				if($flags[1]){
					$this->flowIntoBlock($this->pos->getWorld()->getBlockAt($this->pos->x + 1, $this->pos->y, $this->pos->z), $adjacentDecay, false);
				}

				if($flags[2]){
					$this->flowIntoBlock($this->pos->getWorld()->getBlockAt($this->pos->x, $this->pos->y, $this->pos->z - 1), $adjacentDecay, false);
				}

				if($flags[3]){
					$this->flowIntoBlock($this->pos->getWorld()->getBlockAt($this->pos->x, $this->pos->y, $this->pos->z + 1), $adjacentDecay, false);
				}
			}
		}

		$this->checkForHarden();
	}

	protected function flowIntoBlock(Block $block, int $newFlowDecay, bool $falling) : void{
		if($this->canFlowInto($block) and !($block instanceof Liquid)){
			$new = clone $this;
			$new->falling = $falling;
			$new->decay = $falling ? 0 : $newFlowDecay;

			$ev = new BlockSpreadEvent($block, $this, $new);
			$ev->call();
			if(!$ev->isCancelled()){
				if($block->getId() > 0){
					$this->pos->getWorld()->useBreakOn($block->pos);
				}

				$this->pos->getWorld()->setBlock($block->pos, $ev->getNewState());
			}
		}
	}

	private function calculateFlowCost(int $blockX, int $blockY, int $blockZ, int $accumulatedCost, int $maxCost, int $originOpposite, int $lastOpposite) : int{
		$cost = 1000;

		for($j = 0; $j < 4; ++$j){
			if($j === $originOpposite or $j === $lastOpposite){
				continue;
			}

			$x = $blockX;
			$y = $blockY;
			$z = $blockZ;

			if($j === 0){
				--$x;
			}elseif($j === 1){
				++$x;
			}elseif($j === 2){
				--$z;
			}elseif($j === 3){
				++$z;
			}

			if(!isset($this->flowCostVisited[$hash = World::blockHash($x, $y, $z)])){
				$blockSide = $this->pos->getWorld()->getBlockAt($x, $y, $z);
				if(!$this->canFlowInto($blockSide)){
					$this->flowCostVisited[$hash] = self::BLOCKED;
				}elseif($this->pos->getWorld()->getBlockAt($x, $y - 1, $z)->canBeFlowedInto()){
					$this->flowCostVisited[$hash] = self::CAN_FLOW_DOWN;
				}else{
					$this->flowCostVisited[$hash] = self::CAN_FLOW;
				}
			}

			$status = $this->flowCostVisited[$hash];

			if($status === self::BLOCKED){
				continue;
			}elseif($status === self::CAN_FLOW_DOWN){
				return $accumulatedCost;
			}

			if($accumulatedCost >= $maxCost){
				continue;
			}

			$realCost = $this->calculateFlowCost($x, $y, $z, $accumulatedCost + 1, $maxCost, $originOpposite, $j ^ 0x01);

			if($realCost < $cost){
				$cost = $realCost;
			}
		}

		return $cost;
	}

	/**
	 * @return bool[]
	 */
	private function getOptimalFlowDirections() : array{
		$flowCost = array_fill(0, 4, 1000);
		$maxCost = intdiv(4, $this->getFlowDecayPerBlock());
		for($j = 0; $j < 4; ++$j){
			$x = $this->pos->x;
			$y = $this->pos->y;
			$z = $this->pos->z;

			if($j === 0){
				--$x;
			}elseif($j === 1){
				++$x;
			}elseif($j === 2){
				--$z;
			}elseif($j === 3){
				++$z;
			}
			$block = $this->pos->getWorld()->getBlockAt($x, $y, $z);

			if(!$this->canFlowInto($block)){
				$this->flowCostVisited[World::blockHash($x, $y, $z)] = self::BLOCKED;
				continue;
			}elseif($this->pos->getWorld()->getBlockAt($x, $y - 1, $z)->canBeFlowedInto()){
				$this->flowCostVisited[World::blockHash($x, $y, $z)] = self::CAN_FLOW_DOWN;
				$flowCost[$j] = $maxCost = 0;
			}elseif($maxCost > 0){
				$this->flowCostVisited[World::blockHash($x, $y, $z)] = self::CAN_FLOW;
				$flowCost[$j] = $this->calculateFlowCost($x, $y, $z, 1, $maxCost, $j ^ 0x01, $j ^ 0x01);
				$maxCost = min($maxCost, $flowCost[$j]);
			}
		}

		$this->flowCostVisited = [];

		$minCost = min($flowCost);

		$isOptimalFlowDirection = [];

		for($i = 0; $i < 4; ++$i){
			$isOptimalFlowDirection[$i] = ($flowCost[$i] === $minCost);
		}

		return $isOptimalFlowDirection;
	}

	/** @phpstan-impure */
	private function getSmallestFlowDecay(Block $block, int $decay) : int{
		if(!($block instanceof Liquid) or !$block->isSameType($this)){
			return $decay;
		}

		$blockDecay = $block->decay;

		if($block->isSource()){
			++$this->adjacentSources;
		}elseif($block->falling){
			$blockDecay = 0;
		}

		return ($decay >= 0 && $blockDecay >= $decay) ? $decay : $blockDecay;
	}

	protected function checkForHarden() : bool{
		return false;
	}

	protected function liquidCollide(Block $cause, Block $result) : bool{
		$ev = new BlockFormEvent($this, $result);
		$ev->call();
		if(!$ev->isCancelled()){
			$this->pos->getWorld()->setBlock($this->pos, $ev->getNewState());
			$this->pos->getWorld()->addSound($this->pos->add(0.5, 0.5, 0.5), new FizzSound(2.6 + (lcg_value() - lcg_value()) * 0.8));
		}
		return true;
	}

	protected function canFlowInto(Block $block) : bool{
		return $this->pos->getWorld()->isInWorld($block->pos->x, $block->pos->y, $block->pos->z) and $block->canBeFlowedInto() and !($block instanceof Liquid and $block->isSource()); //TODO: I think this should only be liquids of the same type
	}
}
