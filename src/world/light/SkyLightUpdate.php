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

use pocketmine\world\format\Chunk;
use pocketmine\world\format\HeightArray;
use pocketmine\world\format\LightArray;
use pocketmine\world\utils\SubChunkExplorer;
use pocketmine\world\utils\SubChunkExplorerStatus;
use pocketmine\world\World;
use function max;

class SkyLightUpdate extends LightUpdate{

	/**
	 * @var \SplFixedArray|bool[]
	 * @phpstan-var \SplFixedArray<bool>
	 */
	private $directSkyLightBlockers;

	/**
	 * @param \SplFixedArray|int[]  $lightFilters
	 * @param \SplFixedArray|bool[] $directSkyLightBlockers
	 * @phpstan-param \SplFixedArray<int>  $lightFilters
	 * @phpstan-param \SplFixedArray<bool> $directSkyLightBlockers
	 */
	public function __construct(SubChunkExplorer $subChunkExplorer, \SplFixedArray $lightFilters, \SplFixedArray $directSkyLightBlockers){
		parent::__construct($subChunkExplorer, $lightFilters);
		$this->directSkyLightBlockers = $directSkyLightBlockers;
	}

	protected function getCurrentLightArray() : LightArray{
		return $this->subChunkExplorer->currentSubChunk->getBlockSkyLightArray();
	}

	protected function getEffectiveLight(int $x, int $y, int $z) : int{
		if($y >= World::Y_MAX){
			$this->subChunkExplorer->invalidate();
			return 15;
		}
		return parent::getEffectiveLight($x, $y, $z);
	}

	public function recalculateNode(int $x, int $y, int $z) : void{
		if($this->subChunkExplorer->moveTo($x, $y, $z) === SubChunkExplorerStatus::INVALID){
			return;
		}
		$chunk = $this->subChunkExplorer->currentChunk;

		$oldHeightMap = $chunk->getHeightMap($x & 0xf, $z & 0xf);
		$source = $this->subChunkExplorer->currentSubChunk->getFullBlock($x & 0xf, $y & 0xf, $z & 0xf);

		$yPlusOne = $y + 1;

		if($yPlusOne === $oldHeightMap){ //Block changed directly beneath the heightmap. Check if a block was removed or changed to a different light-filter.
			$newHeightMap = self::recalculateHeightMapColumn($chunk, $x & 0x0f, $z & 0x0f, $this->directSkyLightBlockers);
			$chunk->setHeightMap($x & 0xf, $z & 0xf, $newHeightMap);
		}elseif($yPlusOne > $oldHeightMap){ //Block changed above the heightmap.
			if($this->directSkyLightBlockers[$source]){
				$chunk->setHeightMap($x & 0xf, $z & 0xf, $yPlusOne);
				$newHeightMap = $yPlusOne;
			}else{ //Block changed which has no effect on direct sky light, for example placing or removing glass.
				return;
			}
		}else{ //Block changed below heightmap
			$newHeightMap = $oldHeightMap;
		}

		if($newHeightMap > $oldHeightMap){ //Heightmap increase, block placed, remove sky light
			for($i = $y; $i >= $oldHeightMap; --$i){
				$this->setAndUpdateLight($x, $i, $z, 0); //Remove all light beneath, adjacent recalculation will handle the rest.
			}
		}elseif($newHeightMap < $oldHeightMap){ //Heightmap decrease, block changed or removed, add sky light
			for($i = $y; $i >= $newHeightMap; --$i){
				$this->setAndUpdateLight($x, $i, $z, 15);
			}
		}else{ //No heightmap change, block changed "underground"
			$this->setAndUpdateLight($x, $y, $z, max(0, $this->getHighestAdjacentLight($x, $y, $z) - $this->lightFilters[$source]));
		}
	}

	public function recalculateChunk(int $chunkX, int $chunkZ) : int{
		if($this->subChunkExplorer->moveToChunk($chunkX, 0, $chunkZ) === SubChunkExplorerStatus::INVALID){
			throw new \InvalidArgumentException("Chunk $chunkX $chunkZ does not exist");
		}
		$chunk = $this->subChunkExplorer->currentChunk;

		$newHeightMap = self::recalculateHeightMap($chunk, $this->directSkyLightBlockers);
		$chunk->setHeightMapArray($newHeightMap->getValues());

		//setAndUpdateLight() won't bother propagating from nodes that are already what we want to change them to, so we
		//have to avoid filling full light for any subchunk that contains a heightmap Y coordinate
		$highestHeightMapPlusOne = max($chunk->getHeightMapArray()) + 1;
		$lowestClearSubChunk = ($highestHeightMapPlusOne >> 4) + (($highestHeightMapPlusOne & 0xf) !== 0 ? 1 : 0);
		$chunkHeight = $chunk->getSubChunks()->count();
		for($y = 0; $y < $lowestClearSubChunk && $y < $chunkHeight; $y++){
			$chunk->getSubChunk($y)->setBlockSkyLightArray(LightArray::fill(0));
		}
		for($y = $lowestClearSubChunk, $yMax = $chunkHeight; $y < $yMax; $y++){
			$chunk->getSubChunk($y)->setBlockSkyLightArray(LightArray::fill(15));
		}

		$lightSources = 0;

		$baseX = $chunkX << 4;
		$baseZ = $chunkZ << 4;
		for($x = 0; $x < 16; ++$x){
			for($z = 0; $z < 16; ++$z){
				$currentHeight = $chunk->getHeightMap($x, $z);
				$maxAdjacentHeight = 0;
				if($x !== 0){
					$maxAdjacentHeight = max($maxAdjacentHeight, $chunk->getHeightMap($x - 1, $z));
				}
				if($x !== 15){
					$maxAdjacentHeight = max($maxAdjacentHeight, $chunk->getHeightMap($x + 1, $z));
				}
				if($z !== 0){
					$maxAdjacentHeight = max($maxAdjacentHeight, $chunk->getHeightMap($x, $z - 1));
				}
				if($z !== 15){
					$maxAdjacentHeight = max($maxAdjacentHeight, $chunk->getHeightMap($x, $z + 1));
				}

				/*
				 * We skip the top two blocks between current height and max adjacent (if there's a difference) because:
				 * - the block next to the highest adjacent will do nothing during propagation (it's surrounded by 15s)
				 * - the block below that block will do the same as the node in the highest adjacent
				 * NOTE: If block opacity becomes direction-aware in the future, the second point will become invalid.
				 */
				$nodeColumnEnd = max($currentHeight, $maxAdjacentHeight - 2);

				for($y = $currentHeight; $y <= $nodeColumnEnd; $y++){
					$this->setAndUpdateLight($x + $baseX, $y, $z + $baseZ, 15);
					$lightSources++;
				}
				for($y = $nodeColumnEnd + 1, $yMax = $lowestClearSubChunk * 16; $y < $yMax; $y++){
					if($this->subChunkExplorer->moveTo($x + $baseX, $y, $z + $baseZ) !== SubChunkExplorerStatus::INVALID){
						$this->getCurrentLightArray()->set($x, $y & 0xf, $z, 15);
					}
				}
			}
		}

		return $lightSources;
	}

	/**
	 * Recalculates the heightmap for the whole chunk.
	 *
	 * @param \SplFixedArray|bool[] $directSkyLightBlockers
	 * @phpstan-param \SplFixedArray<bool> $directSkyLightBlockers
	 */
	private static function recalculateHeightMap(Chunk $chunk, \SplFixedArray $directSkyLightBlockers) : HeightArray{
		$maxSubChunkY = $chunk->getSubChunks()->count() - 1;
		for(; $maxSubChunkY >= 0; $maxSubChunkY--){
			if(!$chunk->getSubChunk($maxSubChunkY)->isEmptyFast()){
				break;
			}
		}
		$result = HeightArray::fill(World::Y_MIN);
		if($maxSubChunkY === -1){ //whole column is definitely empty
			return $result;
		}

		for($z = 0; $z < 16; ++$z){
			for($x = 0; $x < 16; ++$x){
				$y = null;
				for($subChunkY = $maxSubChunkY; $subChunkY >= 0; $subChunkY--){
					$subHighestBlockY = $chunk->getSubChunk($subChunkY)->getHighestBlockAt($x, $z);
					if($subHighestBlockY !== null){
						$y = ($subChunkY * 16) + $subHighestBlockY;
						break;
					}
				}

				if($y === null){ //no blocks in the column
					$result->set($x, $z, World::Y_MIN);
				}else{
					for(; $y >= World::Y_MIN; --$y){
						if($directSkyLightBlockers[$chunk->getFullBlock($x, $y, $z)]){
							$result->set($x, $z, $y + 1);
							break;
						}
					}
				}
			}
		}
		return $result;
	}

	/**
	 * Recalculates the heightmap for the block column at the specified X/Z chunk coordinates
	 *
	 * @param int $x 0-15
	 * @param int $z 0-15
	 * @param \SplFixedArray|bool[] $directSkyLightBlockers
	 * @phpstan-param \SplFixedArray<bool> $directSkyLightBlockers
	 *
	 * @return int New calculated heightmap value (0-256 inclusive)
	 */
	private static function recalculateHeightMapColumn(Chunk $chunk, int $x, int $z, \SplFixedArray $directSkyLightBlockers) : int{
		$y = $chunk->getHighestBlockAt($x, $z);
		if($y === null){
			return World::Y_MIN;
		}
		for(; $y >= World::Y_MIN; --$y){
			if($directSkyLightBlockers[$chunk->getFullBlock($x, $y, $z)]){
				break;
			}
		}

		return $y + 1;
	}
}
