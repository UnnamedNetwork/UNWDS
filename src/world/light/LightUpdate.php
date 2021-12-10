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

namespace pocketmine\world\light;

use pocketmine\world\format\LightArray;
use pocketmine\world\format\SubChunk;
use pocketmine\world\utils\SubChunkExplorer;
use pocketmine\world\utils\SubChunkExplorerStatus;
use pocketmine\world\World;
use function max;

//TODO: make light updates asynchronous
abstract class LightUpdate{
	private const ADJACENTS = [
		[ 1,  0,  0],
		[-1,  0,  0],
		[ 0,  1,  0],
		[ 0, -1,  0],
		[ 0,  0,  1],
		[ 0,  0, -1]
	];

	/**
	 * @var \SplFixedArray|int[]
	 * @phpstan-var \SplFixedArray<int>
	 */
	protected $lightFilters;

	/**
	 * @var int[][] blockhash => [x, y, z, new light level]
	 * @phpstan-var array<int, array{int, int, int, int}>
	 */
	protected $updateNodes = [];

	/** @var SubChunkExplorer */
	protected $subChunkExplorer;

	/**
	 * @param \SplFixedArray|int[] $lightFilters
	 * @phpstan-param \SplFixedArray<int> $lightFilters
	 */
	public function __construct(SubChunkExplorer $subChunkExplorer, \SplFixedArray $lightFilters){
		$this->lightFilters = $lightFilters;

		$this->subChunkExplorer = $subChunkExplorer;
	}

	abstract protected function getCurrentLightArray() : LightArray;

	abstract public function recalculateNode(int $x, int $y, int $z) : void;

	/**
	 * Scans for all light sources in the target chunk and adds them to the propagation queue.
	 * This erases preexisting light in the chunk.
	 */
	abstract public function recalculateChunk(int $chunkX, int $chunkZ) : int;

	protected function getEffectiveLight(int $x, int $y, int $z) : int{
		if($this->subChunkExplorer->moveTo($x, $y, $z) !== SubChunkExplorerStatus::INVALID){
			return $this->getCurrentLightArray()->get($x & SubChunk::COORD_MASK, $y & SubChunk::COORD_MASK, $z & SubChunk::COORD_MASK);
		}
		return 0;
	}

	protected function getHighestAdjacentLight(int $x, int $y, int $z) : int{
		$adjacent = 0;
		foreach(self::ADJACENTS as [$ox, $oy, $oz]){
			if(($adjacent = max($adjacent, $this->getEffectiveLight($x + $ox, $y + $oy, $z + $oz))) === 15){
				break;
			}
		}
		return $adjacent;
	}

	public function setAndUpdateLight(int $x, int $y, int $z, int $newLevel) : void{
		$this->updateNodes[World::blockHash($x, $y, $z)] = [$x, $y, $z, $newLevel];
	}

	private function prepareNodes() : LightPropagationContext{
		$context = new LightPropagationContext();
		foreach($this->updateNodes as $blockHash => [$x, $y, $z, $newLevel]){
			if($this->subChunkExplorer->moveTo($x, $y, $z) !== SubChunkExplorerStatus::INVALID){
				$lightArray = $this->getCurrentLightArray();
				$oldLevel = $lightArray->get($x & SubChunk::COORD_MASK, $y & SubChunk::COORD_MASK, $z & SubChunk::COORD_MASK);

				if($oldLevel !== $newLevel){
					$lightArray->set($x & SubChunk::COORD_MASK, $y & SubChunk::COORD_MASK, $z & SubChunk::COORD_MASK, $newLevel);
					if($oldLevel < $newLevel){ //light increased
						$context->spreadVisited[$blockHash] = true;
						$context->spreadQueue->enqueue([$x, $y, $z]);
					}else{ //light removed
						$context->removalVisited[$blockHash] = true;
						$context->removalQueue->enqueue([$x, $y, $z, $oldLevel]);
					}
				}
			}
		}
		return $context;
	}

	public function execute() : int{
		$context = $this->prepareNodes();

		$touched = 0;
		while(!$context->removalQueue->isEmpty()){
			$touched++;
			[$x, $y, $z, $oldAdjacentLight] = $context->removalQueue->dequeue();

			foreach(self::ADJACENTS as [$ox, $oy, $oz]){
				$cx = $x + $ox;
				$cy = $y + $oy;
				$cz = $z + $oz;

				if($this->subChunkExplorer->moveTo($cx, $cy, $cz) !== SubChunkExplorerStatus::INVALID){
					$this->computeRemoveLight($cx, $cy, $cz, $oldAdjacentLight, $context);
				}elseif($this->getEffectiveLight($cx, $cy, $cz) > 0 and !isset($context->spreadVisited[$index = World::blockHash($cx, $cy, $cz)])){
					$context->spreadVisited[$index] = true;
					$context->spreadQueue->enqueue([$cx, $cy, $cz]);
				}
			}
		}

		while(!$context->spreadQueue->isEmpty()){
			$touched++;
			[$x, $y, $z] = $context->spreadQueue->dequeue();

			unset($context->spreadVisited[World::blockHash($x, $y, $z)]);

			$newAdjacentLight = $this->getEffectiveLight($x, $y, $z);
			if($newAdjacentLight <= 0){
				continue;
			}

			foreach(self::ADJACENTS as [$ox, $oy, $oz]){
				$cx = $x + $ox;
				$cy = $y + $oy;
				$cz = $z + $oz;

				if($this->subChunkExplorer->moveTo($cx, $cy, $cz) !== SubChunkExplorerStatus::INVALID){
					$this->computeSpreadLight($cx, $cy, $cz, $newAdjacentLight, $context);
				}
			}
		}

		return $touched;
	}

	protected function computeRemoveLight(int $x, int $y, int $z, int $oldAdjacentLevel, LightPropagationContext $context) : void{
		$lightArray = $this->getCurrentLightArray();
		$lx = $x & SubChunk::COORD_MASK;
		$ly = $y & SubChunk::COORD_MASK;
		$lz = $z & SubChunk::COORD_MASK;
		$current = $lightArray->get($lx, $ly, $lz);

		if($current !== 0 and $current < $oldAdjacentLevel){
			$lightArray->set($lx, $ly, $lz, 0);

			if(!isset($context->removalVisited[$index = World::blockHash($x, $y, $z)])){
				$context->removalVisited[$index] = true;
				if($current > 1){
					$context->removalQueue->enqueue([$x, $y, $z, $current]);
				}
			}
		}elseif($current >= $oldAdjacentLevel){
			if(!isset($context->spreadVisited[$index = World::blockHash($x, $y, $z)])){
				$context->spreadVisited[$index] = true;
				$context->spreadQueue->enqueue([$x, $y, $z]);
			}
		}
	}

	protected function computeSpreadLight(int $x, int $y, int $z, int $newAdjacentLevel, LightPropagationContext $context) : void{
		$lightArray = $this->getCurrentLightArray();
		$lx = $x & SubChunk::COORD_MASK;
		$ly = $y & SubChunk::COORD_MASK;
		$lz = $z & SubChunk::COORD_MASK;
		$current = $lightArray->get($lx, $ly, $lz);
		$potentialLight = $newAdjacentLevel - $this->lightFilters[$this->subChunkExplorer->currentSubChunk->getFullBlock($lx, $ly, $lz)];

		if($current < $potentialLight){
			$lightArray->set($lx, $ly, $lz, $potentialLight);

			if(!isset($context->spreadVisited[$index = World::blockHash($x, $y, $z)]) and $potentialLight > 1){
				$context->spreadVisited[$index] = true;
				$context->spreadQueue->enqueue([$x, $y, $z]);
			}
		}
	}
}
