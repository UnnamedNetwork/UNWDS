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

namespace pocketmine\world\format\io;

use PHPUnit\Framework\TestCase;

class LevelProviderManagerTest extends TestCase{

	/** @var WorldProviderManager */
	private $providerManager;

	protected function setUp() : void{
		$this->providerManager = new WorldProviderManager();
	}

	public function testAddNonClassProvider() : void{
		$this->expectException(\InvalidArgumentException::class);

		$this->providerManager->addProvider("lol", "nope");
	}

	public function testAddAbstractClassProvider() : void{
		$this->expectException(\InvalidArgumentException::class);

		$this->providerManager->addProvider(AbstractWorldProvider::class, "abstract");
	}

	public function testAddInterfaceProvider() : void{
		$this->expectException(\InvalidArgumentException::class);

		$this->providerManager->addProvider(InterfaceWorldProvider::class, "interface");
	}

	public function testAddWrongClassProvider() : void{
		$this->expectException(\InvalidArgumentException::class);

		$this->providerManager->addProvider(LevelProviderManagerTest::class, "bad_class");
	}
}
