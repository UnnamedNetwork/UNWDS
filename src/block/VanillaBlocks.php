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

use pocketmine\utils\CloningRegistryTrait;

/**
 * This doc-block is generated automatically, do not modify it manually.
 * This must be regenerated whenever registry members are added, removed or changed.
 * @see build/generate-registry-annotations.php
 * @generate-registry-docblock
 *
 * @method static WoodenButton ACACIA_BUTTON()
 * @method static WoodenDoor ACACIA_DOOR()
 * @method static WoodenFence ACACIA_FENCE()
 * @method static FenceGate ACACIA_FENCE_GATE()
 * @method static Leaves ACACIA_LEAVES()
 * @method static Log ACACIA_LOG()
 * @method static Planks ACACIA_PLANKS()
 * @method static WoodenPressurePlate ACACIA_PRESSURE_PLATE()
 * @method static Sapling ACACIA_SAPLING()
 * @method static FloorSign ACACIA_SIGN()
 * @method static WoodenSlab ACACIA_SLAB()
 * @method static WoodenStairs ACACIA_STAIRS()
 * @method static WoodenTrapdoor ACACIA_TRAPDOOR()
 * @method static WallSign ACACIA_WALL_SIGN()
 * @method static Wood ACACIA_WOOD()
 * @method static ActivatorRail ACTIVATOR_RAIL()
 * @method static Air AIR()
 * @method static Flower ALLIUM()
 * @method static MushroomStem ALL_SIDED_MUSHROOM_STEM()
 * @method static Opaque ANDESITE()
 * @method static Slab ANDESITE_SLAB()
 * @method static Stair ANDESITE_STAIRS()
 * @method static Wall ANDESITE_WALL()
 * @method static Anvil ANVIL()
 * @method static Flower AZURE_BLUET()
 * @method static Bamboo BAMBOO()
 * @method static BambooSapling BAMBOO_SAPLING()
 * @method static FloorBanner BANNER()
 * @method static Barrel BARREL()
 * @method static Transparent BARRIER()
 * @method static Beacon BEACON()
 * @method static Bed BED()
 * @method static Bedrock BEDROCK()
 * @method static Beetroot BEETROOTS()
 * @method static Bell BELL()
 * @method static WoodenButton BIRCH_BUTTON()
 * @method static WoodenDoor BIRCH_DOOR()
 * @method static WoodenFence BIRCH_FENCE()
 * @method static FenceGate BIRCH_FENCE_GATE()
 * @method static Leaves BIRCH_LEAVES()
 * @method static Log BIRCH_LOG()
 * @method static Planks BIRCH_PLANKS()
 * @method static WoodenPressurePlate BIRCH_PRESSURE_PLATE()
 * @method static Sapling BIRCH_SAPLING()
 * @method static FloorSign BIRCH_SIGN()
 * @method static WoodenSlab BIRCH_SLAB()
 * @method static WoodenStairs BIRCH_STAIRS()
 * @method static WoodenTrapdoor BIRCH_TRAPDOOR()
 * @method static WallSign BIRCH_WALL_SIGN()
 * @method static Wood BIRCH_WOOD()
 * @method static GlazedTerracotta BLACK_GLAZED_TERRACOTTA()
 * @method static Furnace BLAST_FURNACE()
 * @method static GlazedTerracotta BLUE_GLAZED_TERRACOTTA()
 * @method static BlueIce BLUE_ICE()
 * @method static Flower BLUE_ORCHID()
 * @method static Torch BLUE_TORCH()
 * @method static BoneBlock BONE_BLOCK()
 * @method static Bookshelf BOOKSHELF()
 * @method static BrewingStand BREWING_STAND()
 * @method static Opaque BRICKS()
 * @method static Slab BRICK_SLAB()
 * @method static Stair BRICK_STAIRS()
 * @method static Wall BRICK_WALL()
 * @method static GlazedTerracotta BROWN_GLAZED_TERRACOTTA()
 * @method static BrownMushroom BROWN_MUSHROOM()
 * @method static BrownMushroomBlock BROWN_MUSHROOM_BLOCK()
 * @method static Cactus CACTUS()
 * @method static Cake CAKE()
 * @method static Carpet CARPET()
 * @method static Carrot CARROTS()
 * @method static CarvedPumpkin CARVED_PUMPKIN()
 * @method static ChemicalHeat CHEMICAL_HEAT()
 * @method static Chest CHEST()
 * @method static SimplePillar CHISELED_QUARTZ()
 * @method static Opaque CHISELED_RED_SANDSTONE()
 * @method static Opaque CHISELED_SANDSTONE()
 * @method static Opaque CHISELED_STONE_BRICKS()
 * @method static Clay CLAY()
 * @method static Coal COAL()
 * @method static CoalOre COAL_ORE()
 * @method static Opaque COBBLESTONE()
 * @method static Slab COBBLESTONE_SLAB()
 * @method static Stair COBBLESTONE_STAIRS()
 * @method static Wall COBBLESTONE_WALL()
 * @method static Cobweb COBWEB()
 * @method static CocoaBlock COCOA_POD()
 * @method static ChemistryTable COMPOUND_CREATOR()
 * @method static Concrete CONCRETE()
 * @method static ConcretePowder CONCRETE_POWDER()
 * @method static Coral CORAL()
 * @method static CoralBlock CORAL_BLOCK()
 * @method static FloorCoralFan CORAL_FAN()
 * @method static Flower CORNFLOWER()
 * @method static Opaque CRACKED_STONE_BRICKS()
 * @method static CraftingTable CRAFTING_TABLE()
 * @method static Opaque CUT_RED_SANDSTONE()
 * @method static Slab CUT_RED_SANDSTONE_SLAB()
 * @method static Opaque CUT_SANDSTONE()
 * @method static Slab CUT_SANDSTONE_SLAB()
 * @method static GlazedTerracotta CYAN_GLAZED_TERRACOTTA()
 * @method static Flower DANDELION()
 * @method static WoodenButton DARK_OAK_BUTTON()
 * @method static WoodenDoor DARK_OAK_DOOR()
 * @method static WoodenFence DARK_OAK_FENCE()
 * @method static FenceGate DARK_OAK_FENCE_GATE()
 * @method static Leaves DARK_OAK_LEAVES()
 * @method static Log DARK_OAK_LOG()
 * @method static Planks DARK_OAK_PLANKS()
 * @method static WoodenPressurePlate DARK_OAK_PRESSURE_PLATE()
 * @method static Sapling DARK_OAK_SAPLING()
 * @method static FloorSign DARK_OAK_SIGN()
 * @method static WoodenSlab DARK_OAK_SLAB()
 * @method static WoodenStairs DARK_OAK_STAIRS()
 * @method static WoodenTrapdoor DARK_OAK_TRAPDOOR()
 * @method static WallSign DARK_OAK_WALL_SIGN()
 * @method static Wood DARK_OAK_WOOD()
 * @method static Opaque DARK_PRISMARINE()
 * @method static Slab DARK_PRISMARINE_SLAB()
 * @method static Stair DARK_PRISMARINE_STAIRS()
 * @method static DaylightSensor DAYLIGHT_SENSOR()
 * @method static DeadBush DEAD_BUSH()
 * @method static DetectorRail DETECTOR_RAIL()
 * @method static Opaque DIAMOND()
 * @method static DiamondOre DIAMOND_ORE()
 * @method static Opaque DIORITE()
 * @method static Slab DIORITE_SLAB()
 * @method static Stair DIORITE_STAIRS()
 * @method static Wall DIORITE_WALL()
 * @method static Dirt DIRT()
 * @method static DoubleTallGrass DOUBLE_TALLGRASS()
 * @method static DragonEgg DRAGON_EGG()
 * @method static DriedKelp DRIED_KELP()
 * @method static DyedShulkerBox DYED_SHULKER_BOX()
 * @method static Element ELEMENT_ACTINIUM()
 * @method static Element ELEMENT_ALUMINUM()
 * @method static Element ELEMENT_AMERICIUM()
 * @method static Element ELEMENT_ANTIMONY()
 * @method static Element ELEMENT_ARGON()
 * @method static Element ELEMENT_ARSENIC()
 * @method static Element ELEMENT_ASTATINE()
 * @method static Element ELEMENT_BARIUM()
 * @method static Element ELEMENT_BERKELIUM()
 * @method static Element ELEMENT_BERYLLIUM()
 * @method static Element ELEMENT_BISMUTH()
 * @method static Element ELEMENT_BOHRIUM()
 * @method static Element ELEMENT_BORON()
 * @method static Element ELEMENT_BROMINE()
 * @method static Element ELEMENT_CADMIUM()
 * @method static Element ELEMENT_CALCIUM()
 * @method static Element ELEMENT_CALIFORNIUM()
 * @method static Element ELEMENT_CARBON()
 * @method static Element ELEMENT_CERIUM()
 * @method static Element ELEMENT_CESIUM()
 * @method static Element ELEMENT_CHLORINE()
 * @method static Element ELEMENT_CHROMIUM()
 * @method static Element ELEMENT_COBALT()
 * @method static ChemistryTable ELEMENT_CONSTRUCTOR()
 * @method static Element ELEMENT_COPERNICIUM()
 * @method static Element ELEMENT_COPPER()
 * @method static Element ELEMENT_CURIUM()
 * @method static Element ELEMENT_DARMSTADTIUM()
 * @method static Element ELEMENT_DUBNIUM()
 * @method static Element ELEMENT_DYSPROSIUM()
 * @method static Element ELEMENT_EINSTEINIUM()
 * @method static Element ELEMENT_ERBIUM()
 * @method static Element ELEMENT_EUROPIUM()
 * @method static Element ELEMENT_FERMIUM()
 * @method static Element ELEMENT_FLEROVIUM()
 * @method static Element ELEMENT_FLUORINE()
 * @method static Element ELEMENT_FRANCIUM()
 * @method static Element ELEMENT_GADOLINIUM()
 * @method static Element ELEMENT_GALLIUM()
 * @method static Element ELEMENT_GERMANIUM()
 * @method static Element ELEMENT_GOLD()
 * @method static Element ELEMENT_HAFNIUM()
 * @method static Element ELEMENT_HASSIUM()
 * @method static Element ELEMENT_HELIUM()
 * @method static Element ELEMENT_HOLMIUM()
 * @method static Element ELEMENT_HYDROGEN()
 * @method static Element ELEMENT_INDIUM()
 * @method static Element ELEMENT_IODINE()
 * @method static Element ELEMENT_IRIDIUM()
 * @method static Element ELEMENT_IRON()
 * @method static Element ELEMENT_KRYPTON()
 * @method static Element ELEMENT_LANTHANUM()
 * @method static Element ELEMENT_LAWRENCIUM()
 * @method static Element ELEMENT_LEAD()
 * @method static Element ELEMENT_LITHIUM()
 * @method static Element ELEMENT_LIVERMORIUM()
 * @method static Element ELEMENT_LUTETIUM()
 * @method static Element ELEMENT_MAGNESIUM()
 * @method static Element ELEMENT_MANGANESE()
 * @method static Element ELEMENT_MEITNERIUM()
 * @method static Element ELEMENT_MENDELEVIUM()
 * @method static Element ELEMENT_MERCURY()
 * @method static Element ELEMENT_MOLYBDENUM()
 * @method static Element ELEMENT_MOSCOVIUM()
 * @method static Element ELEMENT_NEODYMIUM()
 * @method static Element ELEMENT_NEON()
 * @method static Element ELEMENT_NEPTUNIUM()
 * @method static Element ELEMENT_NICKEL()
 * @method static Element ELEMENT_NIHONIUM()
 * @method static Element ELEMENT_NIOBIUM()
 * @method static Element ELEMENT_NITROGEN()
 * @method static Element ELEMENT_NOBELIUM()
 * @method static Element ELEMENT_OGANESSON()
 * @method static Element ELEMENT_OSMIUM()
 * @method static Element ELEMENT_OXYGEN()
 * @method static Element ELEMENT_PALLADIUM()
 * @method static Element ELEMENT_PHOSPHORUS()
 * @method static Element ELEMENT_PLATINUM()
 * @method static Element ELEMENT_PLUTONIUM()
 * @method static Element ELEMENT_POLONIUM()
 * @method static Element ELEMENT_POTASSIUM()
 * @method static Element ELEMENT_PRASEODYMIUM()
 * @method static Element ELEMENT_PROMETHIUM()
 * @method static Element ELEMENT_PROTACTINIUM()
 * @method static Element ELEMENT_RADIUM()
 * @method static Element ELEMENT_RADON()
 * @method static Element ELEMENT_RHENIUM()
 * @method static Element ELEMENT_RHODIUM()
 * @method static Element ELEMENT_ROENTGENIUM()
 * @method static Element ELEMENT_RUBIDIUM()
 * @method static Element ELEMENT_RUTHENIUM()
 * @method static Element ELEMENT_RUTHERFORDIUM()
 * @method static Element ELEMENT_SAMARIUM()
 * @method static Element ELEMENT_SCANDIUM()
 * @method static Element ELEMENT_SEABORGIUM()
 * @method static Element ELEMENT_SELENIUM()
 * @method static Element ELEMENT_SILICON()
 * @method static Element ELEMENT_SILVER()
 * @method static Element ELEMENT_SODIUM()
 * @method static Element ELEMENT_STRONTIUM()
 * @method static Element ELEMENT_SULFUR()
 * @method static Element ELEMENT_TANTALUM()
 * @method static Element ELEMENT_TECHNETIUM()
 * @method static Element ELEMENT_TELLURIUM()
 * @method static Element ELEMENT_TENNESSINE()
 * @method static Element ELEMENT_TERBIUM()
 * @method static Element ELEMENT_THALLIUM()
 * @method static Element ELEMENT_THORIUM()
 * @method static Element ELEMENT_THULIUM()
 * @method static Element ELEMENT_TIN()
 * @method static Element ELEMENT_TITANIUM()
 * @method static Element ELEMENT_TUNGSTEN()
 * @method static Element ELEMENT_URANIUM()
 * @method static Element ELEMENT_VANADIUM()
 * @method static Element ELEMENT_XENON()
 * @method static Element ELEMENT_YTTERBIUM()
 * @method static Element ELEMENT_YTTRIUM()
 * @method static Opaque ELEMENT_ZERO()
 * @method static Element ELEMENT_ZINC()
 * @method static Element ELEMENT_ZIRCONIUM()
 * @method static Opaque EMERALD()
 * @method static EmeraldOre EMERALD_ORE()
 * @method static EnchantingTable ENCHANTING_TABLE()
 * @method static EnderChest ENDER_CHEST()
 * @method static EndPortalFrame END_PORTAL_FRAME()
 * @method static EndRod END_ROD()
 * @method static Opaque END_STONE()
 * @method static Opaque END_STONE_BRICKS()
 * @method static Slab END_STONE_BRICK_SLAB()
 * @method static Stair END_STONE_BRICK_STAIRS()
 * @method static Wall END_STONE_BRICK_WALL()
 * @method static Slab FAKE_WOODEN_SLAB()
 * @method static Farmland FARMLAND()
 * @method static TallGrass FERN()
 * @method static Fire FIRE()
 * @method static FletchingTable FLETCHING_TABLE()
 * @method static FlowerPot FLOWER_POT()
 * @method static FrostedIce FROSTED_ICE()
 * @method static Furnace FURNACE()
 * @method static Glass GLASS()
 * @method static GlassPane GLASS_PANE()
 * @method static GlowingObsidian GLOWING_OBSIDIAN()
 * @method static Glowstone GLOWSTONE()
 * @method static Opaque GOLD()
 * @method static Opaque GOLD_ORE()
 * @method static Opaque GRANITE()
 * @method static Slab GRANITE_SLAB()
 * @method static Stair GRANITE_STAIRS()
 * @method static Wall GRANITE_WALL()
 * @method static Grass GRASS()
 * @method static GrassPath GRASS_PATH()
 * @method static Gravel GRAVEL()
 * @method static GlazedTerracotta GRAY_GLAZED_TERRACOTTA()
 * @method static GlazedTerracotta GREEN_GLAZED_TERRACOTTA()
 * @method static Torch GREEN_TORCH()
 * @method static HardenedClay HARDENED_CLAY()
 * @method static HardenedGlass HARDENED_GLASS()
 * @method static HardenedGlassPane HARDENED_GLASS_PANE()
 * @method static HayBale HAY_BALE()
 * @method static Hopper HOPPER()
 * @method static Ice ICE()
 * @method static InfestedStone INFESTED_CHISELED_STONE_BRICK()
 * @method static InfestedStone INFESTED_COBBLESTONE()
 * @method static InfestedStone INFESTED_CRACKED_STONE_BRICK()
 * @method static InfestedStone INFESTED_MOSSY_STONE_BRICK()
 * @method static InfestedStone INFESTED_STONE()
 * @method static InfestedStone INFESTED_STONE_BRICK()
 * @method static Opaque INFO_UPDATE()
 * @method static Opaque INFO_UPDATE2()
 * @method static Transparent INVISIBLE_BEDROCK()
 * @method static Opaque IRON()
 * @method static Thin IRON_BARS()
 * @method static Door IRON_DOOR()
 * @method static Opaque IRON_ORE()
 * @method static Trapdoor IRON_TRAPDOOR()
 * @method static ItemFrame ITEM_FRAME()
 * @method static Jukebox JUKEBOX()
 * @method static WoodenButton JUNGLE_BUTTON()
 * @method static WoodenDoor JUNGLE_DOOR()
 * @method static WoodenFence JUNGLE_FENCE()
 * @method static FenceGate JUNGLE_FENCE_GATE()
 * @method static Leaves JUNGLE_LEAVES()
 * @method static Log JUNGLE_LOG()
 * @method static Planks JUNGLE_PLANKS()
 * @method static WoodenPressurePlate JUNGLE_PRESSURE_PLATE()
 * @method static Sapling JUNGLE_SAPLING()
 * @method static FloorSign JUNGLE_SIGN()
 * @method static WoodenSlab JUNGLE_SLAB()
 * @method static WoodenStairs JUNGLE_STAIRS()
 * @method static WoodenTrapdoor JUNGLE_TRAPDOOR()
 * @method static WallSign JUNGLE_WALL_SIGN()
 * @method static Wood JUNGLE_WOOD()
 * @method static ChemistryTable LAB_TABLE()
 * @method static Ladder LADDER()
 * @method static Lantern LANTERN()
 * @method static Opaque LAPIS_LAZULI()
 * @method static LapisOre LAPIS_LAZULI_ORE()
 * @method static DoubleTallGrass LARGE_FERN()
 * @method static Lava LAVA()
 * @method static Opaque LEGACY_STONECUTTER()
 * @method static Lever LEVER()
 * @method static GlazedTerracotta LIGHT_BLUE_GLAZED_TERRACOTTA()
 * @method static GlazedTerracotta LIGHT_GRAY_GLAZED_TERRACOTTA()
 * @method static DoublePlant LILAC()
 * @method static Flower LILY_OF_THE_VALLEY()
 * @method static WaterLily LILY_PAD()
 * @method static GlazedTerracotta LIME_GLAZED_TERRACOTTA()
 * @method static LitPumpkin LIT_PUMPKIN()
 * @method static Loom LOOM()
 * @method static GlazedTerracotta MAGENTA_GLAZED_TERRACOTTA()
 * @method static Magma MAGMA()
 * @method static ChemistryTable MATERIAL_REDUCER()
 * @method static Melon MELON()
 * @method static MelonStem MELON_STEM()
 * @method static Skull MOB_HEAD()
 * @method static MonsterSpawner MONSTER_SPAWNER()
 * @method static Opaque MOSSY_COBBLESTONE()
 * @method static Slab MOSSY_COBBLESTONE_SLAB()
 * @method static Stair MOSSY_COBBLESTONE_STAIRS()
 * @method static Wall MOSSY_COBBLESTONE_WALL()
 * @method static Opaque MOSSY_STONE_BRICKS()
 * @method static Slab MOSSY_STONE_BRICK_SLAB()
 * @method static Stair MOSSY_STONE_BRICK_STAIRS()
 * @method static Wall MOSSY_STONE_BRICK_WALL()
 * @method static MushroomStem MUSHROOM_STEM()
 * @method static Mycelium MYCELIUM()
 * @method static Netherrack NETHERRACK()
 * @method static Opaque NETHER_BRICKS()
 * @method static Fence NETHER_BRICK_FENCE()
 * @method static Slab NETHER_BRICK_SLAB()
 * @method static Stair NETHER_BRICK_STAIRS()
 * @method static Wall NETHER_BRICK_WALL()
 * @method static NetherPortal NETHER_PORTAL()
 * @method static NetherQuartzOre NETHER_QUARTZ_ORE()
 * @method static NetherReactor NETHER_REACTOR_CORE()
 * @method static NetherWartPlant NETHER_WART()
 * @method static Opaque NETHER_WART_BLOCK()
 * @method static Note NOTE_BLOCK()
 * @method static WoodenButton OAK_BUTTON()
 * @method static WoodenDoor OAK_DOOR()
 * @method static WoodenFence OAK_FENCE()
 * @method static FenceGate OAK_FENCE_GATE()
 * @method static Leaves OAK_LEAVES()
 * @method static Log OAK_LOG()
 * @method static Planks OAK_PLANKS()
 * @method static WoodenPressurePlate OAK_PRESSURE_PLATE()
 * @method static Sapling OAK_SAPLING()
 * @method static FloorSign OAK_SIGN()
 * @method static WoodenSlab OAK_SLAB()
 * @method static WoodenStairs OAK_STAIRS()
 * @method static WoodenTrapdoor OAK_TRAPDOOR()
 * @method static WallSign OAK_WALL_SIGN()
 * @method static Wood OAK_WOOD()
 * @method static Opaque OBSIDIAN()
 * @method static GlazedTerracotta ORANGE_GLAZED_TERRACOTTA()
 * @method static Flower ORANGE_TULIP()
 * @method static Flower OXEYE_DAISY()
 * @method static PackedIce PACKED_ICE()
 * @method static DoublePlant PEONY()
 * @method static GlazedTerracotta PINK_GLAZED_TERRACOTTA()
 * @method static Flower PINK_TULIP()
 * @method static Podzol PODZOL()
 * @method static Opaque POLISHED_ANDESITE()
 * @method static Slab POLISHED_ANDESITE_SLAB()
 * @method static Stair POLISHED_ANDESITE_STAIRS()
 * @method static Opaque POLISHED_DIORITE()
 * @method static Slab POLISHED_DIORITE_SLAB()
 * @method static Stair POLISHED_DIORITE_STAIRS()
 * @method static Opaque POLISHED_GRANITE()
 * @method static Slab POLISHED_GRANITE_SLAB()
 * @method static Stair POLISHED_GRANITE_STAIRS()
 * @method static Flower POPPY()
 * @method static Potato POTATOES()
 * @method static PoweredRail POWERED_RAIL()
 * @method static Opaque PRISMARINE()
 * @method static Opaque PRISMARINE_BRICKS()
 * @method static Slab PRISMARINE_BRICKS_SLAB()
 * @method static Stair PRISMARINE_BRICKS_STAIRS()
 * @method static Slab PRISMARINE_SLAB()
 * @method static Stair PRISMARINE_STAIRS()
 * @method static Wall PRISMARINE_WALL()
 * @method static Opaque PUMPKIN()
 * @method static PumpkinStem PUMPKIN_STEM()
 * @method static GlazedTerracotta PURPLE_GLAZED_TERRACOTTA()
 * @method static Torch PURPLE_TORCH()
 * @method static Opaque PURPUR()
 * @method static SimplePillar PURPUR_PILLAR()
 * @method static Slab PURPUR_SLAB()
 * @method static Stair PURPUR_STAIRS()
 * @method static Opaque QUARTZ()
 * @method static SimplePillar QUARTZ_PILLAR()
 * @method static Slab QUARTZ_SLAB()
 * @method static Stair QUARTZ_STAIRS()
 * @method static Rail RAIL()
 * @method static Redstone REDSTONE()
 * @method static RedstoneComparator REDSTONE_COMPARATOR()
 * @method static RedstoneLamp REDSTONE_LAMP()
 * @method static RedstoneOre REDSTONE_ORE()
 * @method static RedstoneRepeater REDSTONE_REPEATER()
 * @method static RedstoneTorch REDSTONE_TORCH()
 * @method static RedstoneWire REDSTONE_WIRE()
 * @method static GlazedTerracotta RED_GLAZED_TERRACOTTA()
 * @method static RedMushroom RED_MUSHROOM()
 * @method static RedMushroomBlock RED_MUSHROOM_BLOCK()
 * @method static Opaque RED_NETHER_BRICKS()
 * @method static Slab RED_NETHER_BRICK_SLAB()
 * @method static Stair RED_NETHER_BRICK_STAIRS()
 * @method static Wall RED_NETHER_BRICK_WALL()
 * @method static Sand RED_SAND()
 * @method static Opaque RED_SANDSTONE()
 * @method static Slab RED_SANDSTONE_SLAB()
 * @method static Stair RED_SANDSTONE_STAIRS()
 * @method static Wall RED_SANDSTONE_WALL()
 * @method static Torch RED_TORCH()
 * @method static Flower RED_TULIP()
 * @method static Reserved6 RESERVED6()
 * @method static DoublePlant ROSE_BUSH()
 * @method static Sand SAND()
 * @method static Opaque SANDSTONE()
 * @method static Slab SANDSTONE_SLAB()
 * @method static Stair SANDSTONE_STAIRS()
 * @method static Wall SANDSTONE_WALL()
 * @method static SeaLantern SEA_LANTERN()
 * @method static SeaPickle SEA_PICKLE()
 * @method static ShulkerBox SHULKER_BOX()
 * @method static Slime SLIME()
 * @method static Furnace SMOKER()
 * @method static Opaque SMOOTH_QUARTZ()
 * @method static Slab SMOOTH_QUARTZ_SLAB()
 * @method static Stair SMOOTH_QUARTZ_STAIRS()
 * @method static Opaque SMOOTH_RED_SANDSTONE()
 * @method static Slab SMOOTH_RED_SANDSTONE_SLAB()
 * @method static Stair SMOOTH_RED_SANDSTONE_STAIRS()
 * @method static Opaque SMOOTH_SANDSTONE()
 * @method static Slab SMOOTH_SANDSTONE_SLAB()
 * @method static Stair SMOOTH_SANDSTONE_STAIRS()
 * @method static Opaque SMOOTH_STONE()
 * @method static Slab SMOOTH_STONE_SLAB()
 * @method static Snow SNOW()
 * @method static SnowLayer SNOW_LAYER()
 * @method static SoulSand SOUL_SAND()
 * @method static Sponge SPONGE()
 * @method static WoodenButton SPRUCE_BUTTON()
 * @method static WoodenDoor SPRUCE_DOOR()
 * @method static WoodenFence SPRUCE_FENCE()
 * @method static FenceGate SPRUCE_FENCE_GATE()
 * @method static Leaves SPRUCE_LEAVES()
 * @method static Log SPRUCE_LOG()
 * @method static Planks SPRUCE_PLANKS()
 * @method static WoodenPressurePlate SPRUCE_PRESSURE_PLATE()
 * @method static Sapling SPRUCE_SAPLING()
 * @method static FloorSign SPRUCE_SIGN()
 * @method static WoodenSlab SPRUCE_SLAB()
 * @method static WoodenStairs SPRUCE_STAIRS()
 * @method static WoodenTrapdoor SPRUCE_TRAPDOOR()
 * @method static WallSign SPRUCE_WALL_SIGN()
 * @method static Wood SPRUCE_WOOD()
 * @method static StainedHardenedClay STAINED_CLAY()
 * @method static StainedGlass STAINED_GLASS()
 * @method static StainedGlassPane STAINED_GLASS_PANE()
 * @method static StainedHardenedGlass STAINED_HARDENED_GLASS()
 * @method static StainedHardenedGlassPane STAINED_HARDENED_GLASS_PANE()
 * @method static Opaque STONE()
 * @method static Opaque STONE_BRICKS()
 * @method static Slab STONE_BRICK_SLAB()
 * @method static Stair STONE_BRICK_STAIRS()
 * @method static Wall STONE_BRICK_WALL()
 * @method static StoneButton STONE_BUTTON()
 * @method static StonePressurePlate STONE_PRESSURE_PLATE()
 * @method static Slab STONE_SLAB()
 * @method static Stair STONE_STAIRS()
 * @method static Log STRIPPED_ACACIA_LOG()
 * @method static Wood STRIPPED_ACACIA_WOOD()
 * @method static Log STRIPPED_BIRCH_LOG()
 * @method static Wood STRIPPED_BIRCH_WOOD()
 * @method static Log STRIPPED_DARK_OAK_LOG()
 * @method static Wood STRIPPED_DARK_OAK_WOOD()
 * @method static Log STRIPPED_JUNGLE_LOG()
 * @method static Wood STRIPPED_JUNGLE_WOOD()
 * @method static Log STRIPPED_OAK_LOG()
 * @method static Wood STRIPPED_OAK_WOOD()
 * @method static Log STRIPPED_SPRUCE_LOG()
 * @method static Wood STRIPPED_SPRUCE_WOOD()
 * @method static Sugarcane SUGARCANE()
 * @method static DoublePlant SUNFLOWER()
 * @method static SweetBerryBush SWEET_BERRY_BUSH()
 * @method static TallGrass TALL_GRASS()
 * @method static TNT TNT()
 * @method static Torch TORCH()
 * @method static TrappedChest TRAPPED_CHEST()
 * @method static Tripwire TRIPWIRE()
 * @method static TripwireHook TRIPWIRE_HOOK()
 * @method static UnderwaterTorch UNDERWATER_TORCH()
 * @method static Vine VINES()
 * @method static WallBanner WALL_BANNER()
 * @method static WallCoralFan WALL_CORAL_FAN()
 * @method static Water WATER()
 * @method static WeightedPressurePlateHeavy WEIGHTED_PRESSURE_PLATE_HEAVY()
 * @method static WeightedPressurePlateLight WEIGHTED_PRESSURE_PLATE_LIGHT()
 * @method static Wheat WHEAT()
 * @method static GlazedTerracotta WHITE_GLAZED_TERRACOTTA()
 * @method static Flower WHITE_TULIP()
 * @method static Wool WOOL()
 * @method static GlazedTerracotta YELLOW_GLAZED_TERRACOTTA()
 */
final class VanillaBlocks{
	use CloningRegistryTrait;

	private function __construct(){
		//NOOP
	}

	protected static function register(string $name, Block $block) : void{
		self::_registryRegister($name, $block);
	}

	/**
	 * @return Block[]
	 */
	public static function getAll() : array{
		//phpstan doesn't support generic traits yet :(
		/** @var Block[] $result */
		$result = self::_registryGetAll();
		return $result;
	}

	protected static function setup() : void{
		$factory = BlockFactory::getInstance();
		self::register("acacia_button", $factory->get(395, 0));
		self::register("acacia_door", $factory->get(196, 0));
		self::register("acacia_fence", $factory->get(85, 4));
		self::register("acacia_fence_gate", $factory->get(187, 0));
		self::register("acacia_leaves", $factory->get(161, 0));
		self::register("acacia_log", $factory->get(162, 0));
		self::register("acacia_planks", $factory->get(5, 4));
		self::register("acacia_pressure_plate", $factory->get(405, 0));
		self::register("acacia_sapling", $factory->get(6, 4));
		self::register("acacia_sign", $factory->get(445, 0));
		self::register("acacia_slab", $factory->get(158, 4));
		self::register("acacia_stairs", $factory->get(163, 0));
		self::register("acacia_trapdoor", $factory->get(400, 0));
		self::register("acacia_wall_sign", $factory->get(446, 2));
		self::register("acacia_wood", $factory->get(467, 4));
		self::register("activator_rail", $factory->get(126, 0));
		self::register("air", $factory->get(0, 0));
		self::register("all_sided_mushroom_stem", $factory->get(99, 15));
		self::register("allium", $factory->get(38, 2));
		self::register("andesite", $factory->get(1, 5));
		self::register("andesite_slab", $factory->get(417, 3));
		self::register("andesite_stairs", $factory->get(426, 0));
		self::register("andesite_wall", $factory->get(139, 4));
		self::register("anvil", $factory->get(145, 0));
		self::register("azure_bluet", $factory->get(38, 3));
		self::register("bamboo", $factory->get(418, 0));
		self::register("bamboo_sapling", $factory->get(419, 0));
		self::register("banner", $factory->get(176, 0));
		self::register("barrel", $factory->get(458, 0));
		self::register("barrier", $factory->get(416, 0));
		self::register("beacon", $factory->get(138, 0));
		self::register("bed", $factory->get(26, 0));
		self::register("bedrock", $factory->get(7, 0));
		self::register("beetroots", $factory->get(244, 0));
		self::register("bell", $factory->get(461, 0));
		self::register("birch_button", $factory->get(396, 0));
		self::register("birch_door", $factory->get(194, 0));
		self::register("birch_fence", $factory->get(85, 2));
		self::register("birch_fence_gate", $factory->get(184, 0));
		self::register("birch_leaves", $factory->get(18, 2));
		self::register("birch_log", $factory->get(17, 2));
		self::register("birch_planks", $factory->get(5, 2));
		self::register("birch_pressure_plate", $factory->get(406, 0));
		self::register("birch_sapling", $factory->get(6, 2));
		self::register("birch_sign", $factory->get(441, 0));
		self::register("birch_slab", $factory->get(158, 2));
		self::register("birch_stairs", $factory->get(135, 0));
		self::register("birch_trapdoor", $factory->get(401, 0));
		self::register("birch_wall_sign", $factory->get(442, 2));
		self::register("birch_wood", $factory->get(467, 2));
		self::register("black_glazed_terracotta", $factory->get(235, 2));
		self::register("blast_furnace", $factory->get(451, 2));
		self::register("blue_glazed_terracotta", $factory->get(231, 2));
		self::register("blue_ice", $factory->get(266, 0));
		self::register("blue_orchid", $factory->get(38, 1));
		self::register("blue_torch", $factory->get(204, 5));
		self::register("bone_block", $factory->get(216, 0));
		self::register("bookshelf", $factory->get(47, 0));
		self::register("brewing_stand", $factory->get(117, 0));
		self::register("brick_slab", $factory->get(44, 4));
		self::register("brick_stairs", $factory->get(108, 0));
		self::register("brick_wall", $factory->get(139, 6));
		self::register("bricks", $factory->get(45, 0));
		self::register("brown_glazed_terracotta", $factory->get(232, 2));
		self::register("brown_mushroom", $factory->get(39, 0));
		self::register("brown_mushroom_block", $factory->get(99, 0));
		self::register("cactus", $factory->get(81, 0));
		self::register("cake", $factory->get(92, 0));
		self::register("carpet", $factory->get(171, 0));
		self::register("carrots", $factory->get(141, 0));
		self::register("carved_pumpkin", $factory->get(410, 0));
		self::register("chemical_heat", $factory->get(192, 0));
		self::register("chest", $factory->get(54, 2));
		self::register("chiseled_quartz", $factory->get(155, 1));
		self::register("chiseled_red_sandstone", $factory->get(179, 1));
		self::register("chiseled_sandstone", $factory->get(24, 1));
		self::register("chiseled_stone_bricks", $factory->get(98, 3));
		self::register("clay", $factory->get(82, 0));
		self::register("coal", $factory->get(173, 0));
		self::register("coal_ore", $factory->get(16, 0));
		self::register("cobblestone", $factory->get(4, 0));
		self::register("cobblestone_slab", $factory->get(44, 3));
		self::register("cobblestone_stairs", $factory->get(67, 0));
		self::register("cobblestone_wall", $factory->get(139, 0));
		self::register("cobweb", $factory->get(30, 0));
		self::register("cocoa_pod", $factory->get(127, 0));
		self::register("compound_creator", $factory->get(238, 0));
		self::register("concrete", $factory->get(236, 0));
		self::register("concrete_powder", $factory->get(237, 0));
		self::register("coral", $factory->get(386, 0));
		self::register("coral_block", $factory->get(387, 0));
		self::register("coral_fan", $factory->get(388, 0));
		self::register("cornflower", $factory->get(38, 9));
		self::register("cracked_stone_bricks", $factory->get(98, 2));
		self::register("crafting_table", $factory->get(58, 0));
		self::register("cut_red_sandstone", $factory->get(179, 2));
		self::register("cut_red_sandstone_slab", $factory->get(421, 4));
		self::register("cut_sandstone", $factory->get(24, 2));
		self::register("cut_sandstone_slab", $factory->get(421, 3));
		self::register("cyan_glazed_terracotta", $factory->get(229, 2));
		self::register("dandelion", $factory->get(37, 0));
		self::register("dark_oak_button", $factory->get(397, 0));
		self::register("dark_oak_door", $factory->get(197, 0));
		self::register("dark_oak_fence", $factory->get(85, 5));
		self::register("dark_oak_fence_gate", $factory->get(186, 0));
		self::register("dark_oak_leaves", $factory->get(161, 1));
		self::register("dark_oak_log", $factory->get(162, 1));
		self::register("dark_oak_planks", $factory->get(5, 5));
		self::register("dark_oak_pressure_plate", $factory->get(407, 0));
		self::register("dark_oak_sapling", $factory->get(6, 5));
		self::register("dark_oak_sign", $factory->get(447, 0));
		self::register("dark_oak_slab", $factory->get(158, 5));
		self::register("dark_oak_stairs", $factory->get(164, 0));
		self::register("dark_oak_trapdoor", $factory->get(402, 0));
		self::register("dark_oak_wall_sign", $factory->get(448, 2));
		self::register("dark_oak_wood", $factory->get(467, 5));
		self::register("dark_prismarine", $factory->get(168, 1));
		self::register("dark_prismarine_slab", $factory->get(182, 3));
		self::register("dark_prismarine_stairs", $factory->get(258, 0));
		self::register("daylight_sensor", $factory->get(151, 0));
		self::register("dead_bush", $factory->get(32, 0));
		self::register("detector_rail", $factory->get(28, 0));
		self::register("diamond", $factory->get(57, 0));
		self::register("diamond_ore", $factory->get(56, 0));
		self::register("diorite", $factory->get(1, 3));
		self::register("diorite_slab", $factory->get(417, 4));
		self::register("diorite_stairs", $factory->get(425, 0));
		self::register("diorite_wall", $factory->get(139, 3));
		self::register("dirt", $factory->get(3, 0));
		self::register("double_tallgrass", $factory->get(175, 2));
		self::register("dragon_egg", $factory->get(122, 0));
		self::register("dried_kelp", $factory->get(394, 0));
		self::register("dyed_shulker_box", $factory->get(218, 0));
		self::register("element_actinium", $factory->get(355, 0));
		self::register("element_aluminum", $factory->get(279, 0));
		self::register("element_americium", $factory->get(361, 0));
		self::register("element_antimony", $factory->get(317, 0));
		self::register("element_argon", $factory->get(284, 0));
		self::register("element_arsenic", $factory->get(299, 0));
		self::register("element_astatine", $factory->get(351, 0));
		self::register("element_barium", $factory->get(322, 0));
		self::register("element_berkelium", $factory->get(363, 0));
		self::register("element_beryllium", $factory->get(270, 0));
		self::register("element_bismuth", $factory->get(349, 0));
		self::register("element_bohrium", $factory->get(373, 0));
		self::register("element_boron", $factory->get(271, 0));
		self::register("element_bromine", $factory->get(301, 0));
		self::register("element_cadmium", $factory->get(314, 0));
		self::register("element_calcium", $factory->get(286, 0));
		self::register("element_californium", $factory->get(364, 0));
		self::register("element_carbon", $factory->get(272, 0));
		self::register("element_cerium", $factory->get(324, 0));
		self::register("element_cesium", $factory->get(321, 0));
		self::register("element_chlorine", $factory->get(283, 0));
		self::register("element_chromium", $factory->get(290, 0));
		self::register("element_cobalt", $factory->get(293, 0));
		self::register("element_constructor", $factory->get(238, 8));
		self::register("element_copernicium", $factory->get(378, 0));
		self::register("element_copper", $factory->get(295, 0));
		self::register("element_curium", $factory->get(362, 0));
		self::register("element_darmstadtium", $factory->get(376, 0));
		self::register("element_dubnium", $factory->get(371, 0));
		self::register("element_dysprosium", $factory->get(332, 0));
		self::register("element_einsteinium", $factory->get(365, 0));
		self::register("element_erbium", $factory->get(334, 0));
		self::register("element_europium", $factory->get(329, 0));
		self::register("element_fermium", $factory->get(366, 0));
		self::register("element_flerovium", $factory->get(380, 0));
		self::register("element_fluorine", $factory->get(275, 0));
		self::register("element_francium", $factory->get(353, 0));
		self::register("element_gadolinium", $factory->get(330, 0));
		self::register("element_gallium", $factory->get(297, 0));
		self::register("element_germanium", $factory->get(298, 0));
		self::register("element_gold", $factory->get(345, 0));
		self::register("element_hafnium", $factory->get(338, 0));
		self::register("element_hassium", $factory->get(374, 0));
		self::register("element_helium", $factory->get(268, 0));
		self::register("element_holmium", $factory->get(333, 0));
		self::register("element_hydrogen", $factory->get(267, 0));
		self::register("element_indium", $factory->get(315, 0));
		self::register("element_iodine", $factory->get(319, 0));
		self::register("element_iridium", $factory->get(343, 0));
		self::register("element_iron", $factory->get(292, 0));
		self::register("element_krypton", $factory->get(302, 0));
		self::register("element_lanthanum", $factory->get(323, 0));
		self::register("element_lawrencium", $factory->get(369, 0));
		self::register("element_lead", $factory->get(348, 0));
		self::register("element_lithium", $factory->get(269, 0));
		self::register("element_livermorium", $factory->get(382, 0));
		self::register("element_lutetium", $factory->get(337, 0));
		self::register("element_magnesium", $factory->get(278, 0));
		self::register("element_manganese", $factory->get(291, 0));
		self::register("element_meitnerium", $factory->get(375, 0));
		self::register("element_mendelevium", $factory->get(367, 0));
		self::register("element_mercury", $factory->get(346, 0));
		self::register("element_molybdenum", $factory->get(308, 0));
		self::register("element_moscovium", $factory->get(381, 0));
		self::register("element_neodymium", $factory->get(326, 0));
		self::register("element_neon", $factory->get(276, 0));
		self::register("element_neptunium", $factory->get(359, 0));
		self::register("element_nickel", $factory->get(294, 0));
		self::register("element_nihonium", $factory->get(379, 0));
		self::register("element_niobium", $factory->get(307, 0));
		self::register("element_nitrogen", $factory->get(273, 0));
		self::register("element_nobelium", $factory->get(368, 0));
		self::register("element_oganesson", $factory->get(384, 0));
		self::register("element_osmium", $factory->get(342, 0));
		self::register("element_oxygen", $factory->get(274, 0));
		self::register("element_palladium", $factory->get(312, 0));
		self::register("element_phosphorus", $factory->get(281, 0));
		self::register("element_platinum", $factory->get(344, 0));
		self::register("element_plutonium", $factory->get(360, 0));
		self::register("element_polonium", $factory->get(350, 0));
		self::register("element_potassium", $factory->get(285, 0));
		self::register("element_praseodymium", $factory->get(325, 0));
		self::register("element_promethium", $factory->get(327, 0));
		self::register("element_protactinium", $factory->get(357, 0));
		self::register("element_radium", $factory->get(354, 0));
		self::register("element_radon", $factory->get(352, 0));
		self::register("element_rhenium", $factory->get(341, 0));
		self::register("element_rhodium", $factory->get(311, 0));
		self::register("element_roentgenium", $factory->get(377, 0));
		self::register("element_rubidium", $factory->get(303, 0));
		self::register("element_ruthenium", $factory->get(310, 0));
		self::register("element_rutherfordium", $factory->get(370, 0));
		self::register("element_samarium", $factory->get(328, 0));
		self::register("element_scandium", $factory->get(287, 0));
		self::register("element_seaborgium", $factory->get(372, 0));
		self::register("element_selenium", $factory->get(300, 0));
		self::register("element_silicon", $factory->get(280, 0));
		self::register("element_silver", $factory->get(313, 0));
		self::register("element_sodium", $factory->get(277, 0));
		self::register("element_strontium", $factory->get(304, 0));
		self::register("element_sulfur", $factory->get(282, 0));
		self::register("element_tantalum", $factory->get(339, 0));
		self::register("element_technetium", $factory->get(309, 0));
		self::register("element_tellurium", $factory->get(318, 0));
		self::register("element_tennessine", $factory->get(383, 0));
		self::register("element_terbium", $factory->get(331, 0));
		self::register("element_thallium", $factory->get(347, 0));
		self::register("element_thorium", $factory->get(356, 0));
		self::register("element_thulium", $factory->get(335, 0));
		self::register("element_tin", $factory->get(316, 0));
		self::register("element_titanium", $factory->get(288, 0));
		self::register("element_tungsten", $factory->get(340, 0));
		self::register("element_uranium", $factory->get(358, 0));
		self::register("element_vanadium", $factory->get(289, 0));
		self::register("element_xenon", $factory->get(320, 0));
		self::register("element_ytterbium", $factory->get(336, 0));
		self::register("element_yttrium", $factory->get(305, 0));
		self::register("element_zero", $factory->get(36, 0));
		self::register("element_zinc", $factory->get(296, 0));
		self::register("element_zirconium", $factory->get(306, 0));
		self::register("emerald", $factory->get(133, 0));
		self::register("emerald_ore", $factory->get(129, 0));
		self::register("enchanting_table", $factory->get(116, 0));
		self::register("end_portal_frame", $factory->get(120, 0));
		self::register("end_rod", $factory->get(208, 0));
		self::register("end_stone", $factory->get(121, 0));
		self::register("end_stone_brick_slab", $factory->get(417, 0));
		self::register("end_stone_brick_stairs", $factory->get(433, 0));
		self::register("end_stone_brick_wall", $factory->get(139, 10));
		self::register("end_stone_bricks", $factory->get(206, 0));
		self::register("ender_chest", $factory->get(130, 2));
		self::register("fake_wooden_slab", $factory->get(44, 2));
		self::register("farmland", $factory->get(60, 0));
		self::register("fern", $factory->get(31, 2));
		self::register("fire", $factory->get(51, 0));
		self::register("fletching_table", $factory->get(456, 0));
		self::register("flower_pot", $factory->get(140, 0));
		self::register("frosted_ice", $factory->get(207, 0));
		self::register("furnace", $factory->get(61, 2));
		self::register("glass", $factory->get(20, 0));
		self::register("glass_pane", $factory->get(102, 0));
		self::register("glowing_obsidian", $factory->get(246, 0));
		self::register("glowstone", $factory->get(89, 0));
		self::register("gold", $factory->get(41, 0));
		self::register("gold_ore", $factory->get(14, 0));
		self::register("granite", $factory->get(1, 1));
		self::register("granite_slab", $factory->get(417, 6));
		self::register("granite_stairs", $factory->get(424, 0));
		self::register("granite_wall", $factory->get(139, 2));
		self::register("grass", $factory->get(2, 0));
		self::register("grass_path", $factory->get(198, 0));
		self::register("gravel", $factory->get(13, 0));
		self::register("gray_glazed_terracotta", $factory->get(227, 2));
		self::register("green_glazed_terracotta", $factory->get(233, 2));
		self::register("green_torch", $factory->get(202, 13));
		self::register("hardened_clay", $factory->get(172, 0));
		self::register("hardened_glass", $factory->get(253, 0));
		self::register("hardened_glass_pane", $factory->get(190, 0));
		self::register("hay_bale", $factory->get(170, 0));
		self::register("hopper", $factory->get(154, 0));
		self::register("ice", $factory->get(79, 0));
		self::register("infested_chiseled_stone_brick", $factory->get(97, 5));
		self::register("infested_cobblestone", $factory->get(97, 1));
		self::register("infested_cracked_stone_brick", $factory->get(97, 4));
		self::register("infested_mossy_stone_brick", $factory->get(97, 3));
		self::register("infested_stone", $factory->get(97, 0));
		self::register("infested_stone_brick", $factory->get(97, 2));
		self::register("info_update", $factory->get(248, 0));
		self::register("info_update2", $factory->get(249, 0));
		self::register("invisible_bedrock", $factory->get(95, 0));
		self::register("iron", $factory->get(42, 0));
		self::register("iron_bars", $factory->get(101, 0));
		self::register("iron_door", $factory->get(71, 0));
		self::register("iron_ore", $factory->get(15, 0));
		self::register("iron_trapdoor", $factory->get(167, 0));
		self::register("item_frame", $factory->get(199, 0));
		self::register("jukebox", $factory->get(84, 0));
		self::register("jungle_button", $factory->get(398, 0));
		self::register("jungle_door", $factory->get(195, 0));
		self::register("jungle_fence", $factory->get(85, 3));
		self::register("jungle_fence_gate", $factory->get(185, 0));
		self::register("jungle_leaves", $factory->get(18, 3));
		self::register("jungle_log", $factory->get(17, 3));
		self::register("jungle_planks", $factory->get(5, 3));
		self::register("jungle_pressure_plate", $factory->get(408, 0));
		self::register("jungle_sapling", $factory->get(6, 3));
		self::register("jungle_sign", $factory->get(443, 0));
		self::register("jungle_slab", $factory->get(158, 3));
		self::register("jungle_stairs", $factory->get(136, 0));
		self::register("jungle_trapdoor", $factory->get(403, 0));
		self::register("jungle_wall_sign", $factory->get(444, 2));
		self::register("jungle_wood", $factory->get(467, 3));
		self::register("lab_table", $factory->get(238, 12));
		self::register("ladder", $factory->get(65, 2));
		self::register("lantern", $factory->get(463, 0));
		self::register("lapis_lazuli", $factory->get(22, 0));
		self::register("lapis_lazuli_ore", $factory->get(21, 0));
		self::register("large_fern", $factory->get(175, 3));
		self::register("lava", $factory->get(10, 0));
		self::register("legacy_stonecutter", $factory->get(245, 0));
		self::register("lever", $factory->get(69, 0));
		self::register("light_blue_glazed_terracotta", $factory->get(223, 2));
		self::register("light_gray_glazed_terracotta", $factory->get(228, 2));
		self::register("lilac", $factory->get(175, 1));
		self::register("lily_of_the_valley", $factory->get(38, 10));
		self::register("lily_pad", $factory->get(111, 0));
		self::register("lime_glazed_terracotta", $factory->get(225, 2));
		self::register("lit_pumpkin", $factory->get(91, 0));
		self::register("loom", $factory->get(459, 0));
		self::register("magenta_glazed_terracotta", $factory->get(222, 2));
		self::register("magma", $factory->get(213, 0));
		self::register("material_reducer", $factory->get(238, 4));
		self::register("melon", $factory->get(103, 0));
		self::register("melon_stem", $factory->get(105, 0));
		self::register("mob_head", $factory->get(144, 2));
		self::register("monster_spawner", $factory->get(52, 0));
		self::register("mossy_cobblestone", $factory->get(48, 0));
		self::register("mossy_cobblestone_slab", $factory->get(182, 5));
		self::register("mossy_cobblestone_stairs", $factory->get(434, 0));
		self::register("mossy_cobblestone_wall", $factory->get(139, 1));
		self::register("mossy_stone_brick_slab", $factory->get(421, 0));
		self::register("mossy_stone_brick_stairs", $factory->get(430, 0));
		self::register("mossy_stone_brick_wall", $factory->get(139, 8));
		self::register("mossy_stone_bricks", $factory->get(98, 1));
		self::register("mushroom_stem", $factory->get(99, 10));
		self::register("mycelium", $factory->get(110, 0));
		self::register("nether_brick_fence", $factory->get(113, 0));
		self::register("nether_brick_slab", $factory->get(44, 7));
		self::register("nether_brick_stairs", $factory->get(114, 0));
		self::register("nether_brick_wall", $factory->get(139, 9));
		self::register("nether_bricks", $factory->get(112, 0));
		self::register("nether_portal", $factory->get(90, 1));
		self::register("nether_quartz_ore", $factory->get(153, 0));
		self::register("nether_reactor_core", $factory->get(247, 0));
		self::register("nether_wart", $factory->get(115, 0));
		self::register("nether_wart_block", $factory->get(214, 0));
		self::register("netherrack", $factory->get(87, 0));
		self::register("note_block", $factory->get(25, 0));
		self::register("oak_button", $factory->get(143, 0));
		self::register("oak_door", $factory->get(64, 0));
		self::register("oak_fence", $factory->get(85, 0));
		self::register("oak_fence_gate", $factory->get(107, 0));
		self::register("oak_leaves", $factory->get(18, 0));
		self::register("oak_log", $factory->get(17, 0));
		self::register("oak_planks", $factory->get(5, 0));
		self::register("oak_pressure_plate", $factory->get(72, 0));
		self::register("oak_sapling", $factory->get(6, 0));
		self::register("oak_sign", $factory->get(63, 0));
		self::register("oak_slab", $factory->get(158, 0));
		self::register("oak_stairs", $factory->get(53, 0));
		self::register("oak_trapdoor", $factory->get(96, 0));
		self::register("oak_wall_sign", $factory->get(68, 2));
		self::register("oak_wood", $factory->get(467, 0));
		self::register("obsidian", $factory->get(49, 0));
		self::register("orange_glazed_terracotta", $factory->get(221, 2));
		self::register("orange_tulip", $factory->get(38, 5));
		self::register("oxeye_daisy", $factory->get(38, 8));
		self::register("packed_ice", $factory->get(174, 0));
		self::register("peony", $factory->get(175, 5));
		self::register("pink_glazed_terracotta", $factory->get(226, 2));
		self::register("pink_tulip", $factory->get(38, 7));
		self::register("podzol", $factory->get(243, 0));
		self::register("polished_andesite", $factory->get(1, 6));
		self::register("polished_andesite_slab", $factory->get(417, 2));
		self::register("polished_andesite_stairs", $factory->get(429, 0));
		self::register("polished_diorite", $factory->get(1, 4));
		self::register("polished_diorite_slab", $factory->get(417, 5));
		self::register("polished_diorite_stairs", $factory->get(428, 0));
		self::register("polished_granite", $factory->get(1, 2));
		self::register("polished_granite_slab", $factory->get(417, 7));
		self::register("polished_granite_stairs", $factory->get(427, 0));
		self::register("poppy", $factory->get(38, 0));
		self::register("potatoes", $factory->get(142, 0));
		self::register("powered_rail", $factory->get(27, 0));
		self::register("prismarine", $factory->get(168, 0));
		self::register("prismarine_bricks", $factory->get(168, 2));
		self::register("prismarine_bricks_slab", $factory->get(182, 4));
		self::register("prismarine_bricks_stairs", $factory->get(259, 0));
		self::register("prismarine_slab", $factory->get(182, 2));
		self::register("prismarine_stairs", $factory->get(257, 0));
		self::register("prismarine_wall", $factory->get(139, 11));
		self::register("pumpkin", $factory->get(86, 0));
		self::register("pumpkin_stem", $factory->get(104, 0));
		self::register("purple_glazed_terracotta", $factory->get(219, 2));
		self::register("purple_torch", $factory->get(204, 13));
		self::register("purpur", $factory->get(201, 0));
		self::register("purpur_pillar", $factory->get(201, 2));
		self::register("purpur_slab", $factory->get(182, 1));
		self::register("purpur_stairs", $factory->get(203, 0));
		self::register("quartz", $factory->get(155, 0));
		self::register("quartz_pillar", $factory->get(155, 2));
		self::register("quartz_slab", $factory->get(44, 6));
		self::register("quartz_stairs", $factory->get(156, 0));
		self::register("rail", $factory->get(66, 0));
		self::register("red_glazed_terracotta", $factory->get(234, 2));
		self::register("red_mushroom", $factory->get(40, 0));
		self::register("red_mushroom_block", $factory->get(100, 0));
		self::register("red_nether_brick_slab", $factory->get(182, 7));
		self::register("red_nether_brick_stairs", $factory->get(439, 0));
		self::register("red_nether_brick_wall", $factory->get(139, 13));
		self::register("red_nether_bricks", $factory->get(215, 0));
		self::register("red_sand", $factory->get(12, 1));
		self::register("red_sandstone", $factory->get(179, 0));
		self::register("red_sandstone_slab", $factory->get(182, 0));
		self::register("red_sandstone_stairs", $factory->get(180, 0));
		self::register("red_sandstone_wall", $factory->get(139, 12));
		self::register("red_torch", $factory->get(202, 5));
		self::register("red_tulip", $factory->get(38, 4));
		self::register("redstone", $factory->get(152, 0));
		self::register("redstone_comparator", $factory->get(149, 0));
		self::register("redstone_lamp", $factory->get(123, 0));
		self::register("redstone_ore", $factory->get(73, 0));
		self::register("redstone_repeater", $factory->get(93, 0));
		self::register("redstone_torch", $factory->get(76, 5));
		self::register("redstone_wire", $factory->get(55, 0));
		self::register("reserved6", $factory->get(255, 0));
		self::register("rose_bush", $factory->get(175, 4));
		self::register("sand", $factory->get(12, 0));
		self::register("sandstone", $factory->get(24, 0));
		self::register("sandstone_slab", $factory->get(44, 1));
		self::register("sandstone_stairs", $factory->get(128, 0));
		self::register("sandstone_wall", $factory->get(139, 5));
		self::register("sea_lantern", $factory->get(169, 0));
		self::register("sea_pickle", $factory->get(411, 0));
		self::register("shulker_box", $factory->get(205, 0));
		self::register("slime", $factory->get(165, 0));
		self::register("smoker", $factory->get(453, 2));
		self::register("smooth_quartz", $factory->get(155, 3));
		self::register("smooth_quartz_slab", $factory->get(421, 1));
		self::register("smooth_quartz_stairs", $factory->get(440, 0));
		self::register("smooth_red_sandstone", $factory->get(179, 3));
		self::register("smooth_red_sandstone_slab", $factory->get(417, 1));
		self::register("smooth_red_sandstone_stairs", $factory->get(431, 0));
		self::register("smooth_sandstone", $factory->get(24, 3));
		self::register("smooth_sandstone_slab", $factory->get(182, 6));
		self::register("smooth_sandstone_stairs", $factory->get(432, 0));
		self::register("smooth_stone", $factory->get(438, 0));
		self::register("smooth_stone_slab", $factory->get(44, 0));
		self::register("snow", $factory->get(80, 0));
		self::register("snow_layer", $factory->get(78, 0));
		self::register("soul_sand", $factory->get(88, 0));
		self::register("sponge", $factory->get(19, 0));
		self::register("spruce_button", $factory->get(399, 0));
		self::register("spruce_door", $factory->get(193, 0));
		self::register("spruce_fence", $factory->get(85, 1));
		self::register("spruce_fence_gate", $factory->get(183, 0));
		self::register("spruce_leaves", $factory->get(18, 1));
		self::register("spruce_log", $factory->get(17, 1));
		self::register("spruce_planks", $factory->get(5, 1));
		self::register("spruce_pressure_plate", $factory->get(409, 0));
		self::register("spruce_sapling", $factory->get(6, 1));
		self::register("spruce_sign", $factory->get(436, 0));
		self::register("spruce_slab", $factory->get(158, 1));
		self::register("spruce_stairs", $factory->get(134, 0));
		self::register("spruce_trapdoor", $factory->get(404, 0));
		self::register("spruce_wall_sign", $factory->get(437, 2));
		self::register("spruce_wood", $factory->get(467, 1));
		self::register("stained_clay", $factory->get(159, 0));
		self::register("stained_glass", $factory->get(241, 0));
		self::register("stained_glass_pane", $factory->get(160, 0));
		self::register("stained_hardened_glass", $factory->get(254, 0));
		self::register("stained_hardened_glass_pane", $factory->get(191, 0));
		self::register("stone", $factory->get(1, 0));
		self::register("stone_brick_slab", $factory->get(44, 5));
		self::register("stone_brick_stairs", $factory->get(109, 0));
		self::register("stone_brick_wall", $factory->get(139, 7));
		self::register("stone_bricks", $factory->get(98, 0));
		self::register("stone_button", $factory->get(77, 0));
		self::register("stone_pressure_plate", $factory->get(70, 0));
		self::register("stone_slab", $factory->get(421, 2));
		self::register("stone_stairs", $factory->get(435, 0));
		self::register("stripped_acacia_log", $factory->get(263, 0));
		self::register("stripped_acacia_wood", $factory->get(467, 12));
		self::register("stripped_birch_log", $factory->get(261, 0));
		self::register("stripped_birch_wood", $factory->get(467, 10));
		self::register("stripped_dark_oak_log", $factory->get(264, 0));
		self::register("stripped_dark_oak_wood", $factory->get(467, 13));
		self::register("stripped_jungle_log", $factory->get(262, 0));
		self::register("stripped_jungle_wood", $factory->get(467, 11));
		self::register("stripped_oak_log", $factory->get(265, 0));
		self::register("stripped_oak_wood", $factory->get(467, 8));
		self::register("stripped_spruce_log", $factory->get(260, 0));
		self::register("stripped_spruce_wood", $factory->get(467, 9));
		self::register("sugarcane", $factory->get(83, 0));
		self::register("sunflower", $factory->get(175, 0));
		self::register("sweet_berry_bush", $factory->get(462, 0));
		self::register("tall_grass", $factory->get(31, 1));
		self::register("tnt", $factory->get(46, 0));
		self::register("torch", $factory->get(50, 5));
		self::register("trapped_chest", $factory->get(146, 2));
		self::register("tripwire", $factory->get(132, 0));
		self::register("tripwire_hook", $factory->get(131, 0));
		self::register("underwater_torch", $factory->get(239, 5));
		self::register("vines", $factory->get(106, 0));
		self::register("wall_banner", $factory->get(177, 2));
		self::register("wall_coral_fan", $factory->get(390, 0));
		self::register("water", $factory->get(8, 0));
		self::register("weighted_pressure_plate_heavy", $factory->get(148, 0));
		self::register("weighted_pressure_plate_light", $factory->get(147, 0));
		self::register("wheat", $factory->get(59, 0));
		self::register("white_glazed_terracotta", $factory->get(220, 2));
		self::register("white_tulip", $factory->get(38, 6));
		self::register("wool", $factory->get(35, 0));
		self::register("yellow_glazed_terracotta", $factory->get(224, 2));
	}
}
