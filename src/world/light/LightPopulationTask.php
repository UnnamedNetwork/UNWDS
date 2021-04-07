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

use pocketmine\block\BlockFactory;
use pocketmine\scheduler\AsyncTask;
use pocketmine\world\format\Chunk;
use pocketmine\world\format\io\FastChunkSerializer;
use pocketmine\world\format\LightArray;
use pocketmine\world\SimpleChunkManager;
use pocketmine\world\utils\SubChunkExplorer;
use pocketmine\world\World;
use function igbinary_serialize;
use function igbinary_unserialize;

class LightPopulationTask extends AsyncTask{
	private const TLS_KEY_WORLD = "world";

	/** @var string */
	public $chunk;

	/** @var int */
	private $chunkX;
	/** @var int */
	private $chunkZ;

	/** @var string */
	private $resultHeightMap;
	/** @var string */
	private $resultSkyLightArrays;
	/** @var string */
	private $resultBlockLightArrays;

	public function __construct(World $world, int $chunkX, int $chunkZ, Chunk $chunk){
		$this->storeLocal(self::TLS_KEY_WORLD, $world);
		[$this->chunkX, $this->chunkZ] = [$chunkX, $chunkZ];
		$chunk->setLightPopulated(null);
		$this->chunk = FastChunkSerializer::serialize($chunk);
	}

	public function onRun() : void{
		$chunk = FastChunkSerializer::deserialize($this->chunk);

		$manager = new SimpleChunkManager(World::Y_MIN, World::Y_MAX);
		$manager->setChunk($this->chunkX, $this->chunkZ, $chunk);

		$blockFactory = BlockFactory::getInstance();
		foreach([
			"Block" => new BlockLightUpdate(new SubChunkExplorer($manager), $blockFactory->lightFilter, $blockFactory->light),
			"Sky" => new SkyLightUpdate(new SubChunkExplorer($manager), $blockFactory->lightFilter, $blockFactory->blocksDirectSkyLight),
		] as $name => $update){
			$update->recalculateChunk($this->chunkX, $this->chunkZ);
			$update->execute();
		}

		$chunk->setLightPopulated();

		$this->resultHeightMap = igbinary_serialize($chunk->getHeightMapArray());
		$skyLightArrays = [];
		$blockLightArrays = [];
		foreach($chunk->getSubChunks() as $y => $subChunk){
			$skyLightArrays[$y] = $subChunk->getBlockSkyLightArray();
			$blockLightArrays[$y] = $subChunk->getBlockLightArray();
		}
		$this->resultSkyLightArrays = igbinary_serialize($skyLightArrays);
		$this->resultBlockLightArrays = igbinary_serialize($blockLightArrays);
	}

	public function onCompletion() : void{
		/** @var World $world */
		$world = $this->fetchLocal(self::TLS_KEY_WORLD);
		if(!$world->isClosed() and ($chunk = $world->getChunk($this->chunkX, $this->chunkZ)) !== null){
			//TODO: calculated light information might not be valid if the terrain changed during light calculation

			/** @var int[] $heightMapArray */
			$heightMapArray = igbinary_unserialize($this->resultHeightMap);
			$chunk->setHeightMapArray($heightMapArray);

			/** @var LightArray[] $skyLightArrays */
			$skyLightArrays = igbinary_unserialize($this->resultSkyLightArrays);
			/** @var LightArray[] $blockLightArrays */
			$blockLightArrays = igbinary_unserialize($this->resultBlockLightArrays);

			foreach($skyLightArrays as $y => $array){
				$chunk->getSubChunk($y)->setBlockSkyLightArray($array);
			}
			foreach($blockLightArrays as $y => $array){
				$chunk->getSubChunk($y)->setBlockLightArray($array);
			}
			$chunk->setLightPopulated();
		}
	}
}
