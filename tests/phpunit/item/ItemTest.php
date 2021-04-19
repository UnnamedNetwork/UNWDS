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

use PHPUnit\Framework\TestCase;
use pocketmine\block\BlockFactory;
use pocketmine\item\enchantment\Enchantment;
use pocketmine\item\enchantment\EnchantmentInstance;

class ItemTest extends TestCase{

	public function setUp() : void{
		BlockFactory::init();
		ItemFactory::init();
		Enchantment::init();
	}

	/**
	 * Test for issue #1145 (items aren't considered equal after NBT serializing and deserializing
	 */
	public function testItemEquals() : void{
		$item = ItemFactory::get(Item::STONE)->setCustomName("HI");
		$item2 = Item::nbtDeserialize($item->nbtSerialize());
		self::assertTrue($item2->equals($item));
		self::assertTrue($item->equals($item2));
	}

	/**
	 * Test that same items without NBT are considered equal
	 */
	public function testItemEqualsNoNbt() : void{
		$item1 = ItemFactory::get(Item::DIAMOND_SWORD);
		$item2 = clone $item1;
		self::assertTrue($item1->equals($item2));
	}

	/**
	 * Tests that blocks are considered to be valid registered items
	 */
	public function testItemBlockRegistered() : void{
		for($id = 0; $id < 256; ++$id){
			self::assertEquals(BlockFactory::isRegistered($id), ItemFactory::isRegistered($id));
		}
	}

	/**
	 * Tests that when all enchantments are removed from an item, the "ench" tag is removed as well
	 */
	public function testEnchantmentRemoval() : void{
		$item = ItemFactory::get(Item::DIAMOND_SWORD);
		$item->addEnchantment(new EnchantmentInstance(Enchantment::getEnchantment(Enchantment::SHARPNESS)));
		$item->removeEnchantment(Enchantment::SHARPNESS);
		self::assertNull($item->getNamedTag()->getTag(Item::TAG_ENCH));
	}

	/**
	 * @return mixed[][]
	 * @phpstan-return list<array{string,int,int}>
	 */
	public function itemFromStringProvider() : array{
		return [
			["dye:4", ItemIds::DYE, 4],
			["351", ItemIds::DYE, 0],
			["351:4", ItemIds::DYE, 4],
			["stone:3", ItemIds::STONE, 3],
			["minecraft:string", ItemIds::STRING, 0],
			["diamond_pickaxe", ItemIds::DIAMOND_PICKAXE, 0],
			["diamond_pickaxe:5", ItemIds::DIAMOND_PICKAXE, 5]
		];
	}

	/**
	 * @dataProvider itemFromStringProvider
	 */
	public function testFromStringSingle(string $string, int $id, int $meta) : void{
		$item = ItemFactory::fromStringSingle($string);

		self::assertEquals($id, $item->getId());
		self::assertEquals($meta, $item->getDamage());
	}
}
