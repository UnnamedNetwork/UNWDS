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

namespace pocketmine\world\format\io\data;

use pocketmine\math\Vector3;
use pocketmine\nbt\tag\CompoundTag;
use pocketmine\nbt\tag\IntTag;
use pocketmine\world\format\io\exception\CorruptedWorldException;
use pocketmine\world\format\io\exception\UnsupportedWorldFormatException;
use pocketmine\world\format\io\WorldData;
use function file_exists;

abstract class BaseNbtWorldData implements WorldData{

	/** @var string */
	protected $dataPath;

	/** @var CompoundTag */
	protected $compoundTag;

	/**
	 * @throws CorruptedWorldException
	 * @throws UnsupportedWorldFormatException
	 */
	public function __construct(string $dataPath){
		$this->dataPath = $dataPath;

		if(!file_exists($this->dataPath)){
			throw new CorruptedWorldException("World data not found at $dataPath");
		}

		try{
			$this->compoundTag = $this->load();
		}catch(CorruptedWorldException $e){
			throw new CorruptedWorldException("Corrupted world data: " . $e->getMessage(), 0, $e);
		}
		$this->fix();
	}

	/**
	 * @throws CorruptedWorldException
	 * @throws UnsupportedWorldFormatException
	 */
	abstract protected function load() : CompoundTag;

	/**
	 * @throws CorruptedWorldException
	 * @throws UnsupportedWorldFormatException
	 */
	abstract protected function fix() : void;

	/**
	 * Hack to fix worlds broken previously by older versions of PocketMine-MP which incorrectly saved classpaths of
	 * generators into level.dat on imported (not generated) worlds.
	 *
	 * This should only have affected leveldb worlds as far as I know, because PC format worlds include the
	 * generatorName tag by default. However, MCPE leveldb ones didn't, and so they would get filled in with something
	 * broken.
	 *
	 * This bug took a long time to get found because previously the generator manager would just return the default
	 * generator silently on failure to identify the correct generator, which caused lots of unexpected bugs.
	 *
	 * Only classnames which were written into the level.dat from "fixing" the level data are included here. These are
	 * hardcoded to avoid problems fixing broken worlds in the future if these classes get moved, renamed or removed.
	 *
	 * @param string $className Classname saved in level.dat
	 *
	 * @return null|string Name of the correct generator to replace the broken value
	 */
	protected static function hackyFixForGeneratorClasspathInLevelDat(string $className) : ?string{
		//THESE ARE DELIBERATELY HARDCODED, DO NOT CHANGE!
		switch($className){
			/** @noinspection ClassConstantCanBeUsedInspection */
			case 'pocketmine\level\generator\normal\Normal':
				return "normal";
			/** @noinspection ClassConstantCanBeUsedInspection */
			case 'pocketmine\level\generator\Flat':
				return "flat";
		}

		return null;
	}

	public function getCompoundTag() : CompoundTag{
		return $this->compoundTag;
	}

	/* The below are common between PC and PE */

	public function getName() : string{
		return $this->compoundTag->getString("LevelName");
	}

	public function getGenerator() : string{
		return $this->compoundTag->getString("generatorName", "DEFAULT");
	}

	public function getGeneratorOptions() : array{
		return ["preset" => $this->compoundTag->getString("generatorOptions", "")];
	}

	public function getSeed() : int{
		return $this->compoundTag->getLong("RandomSeed");
	}

	public function getTime() : int{
		if(($timeTag = $this->compoundTag->getTag("Time")) instanceof IntTag){ //some older PM worlds had this in the wrong format
			return $timeTag->getValue();
		}
		return $this->compoundTag->getLong("Time", 0);
	}

	public function setTime(int $value) : void{
		$this->compoundTag->setLong("Time", $value);
	}

	public function getSpawn() : Vector3{
		return new Vector3($this->compoundTag->getInt("SpawnX"), $this->compoundTag->getInt("SpawnY"), $this->compoundTag->getInt("SpawnZ"));
	}

	public function setSpawn(Vector3 $pos) : void{
		$this->compoundTag->setInt("SpawnX", $pos->getFloorX());
		$this->compoundTag->setInt("SpawnY", $pos->getFloorY());
		$this->compoundTag->setInt("SpawnZ", $pos->getFloorZ());
	}

}
