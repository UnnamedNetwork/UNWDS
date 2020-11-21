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

namespace pocketmine\network\mcpe\protocol;

use pocketmine\utils\Binary;

use pocketmine\math\Vector3;
use pocketmine\nbt\NetworkLittleEndianNBTStream;
use pocketmine\network\mcpe\NetworkSession;
use pocketmine\network\mcpe\protocol\types\BlockPaletteEntry;
use pocketmine\network\mcpe\protocol\types\EducationEditionOffer;
use pocketmine\network\mcpe\protocol\types\Experiments;
use pocketmine\network\mcpe\protocol\types\GameRuleType;
use pocketmine\network\mcpe\protocol\types\GeneratorType;
use pocketmine\network\mcpe\protocol\types\ItemTypeEntry;
use pocketmine\network\mcpe\protocol\types\MultiplayerGameVisibility;
use pocketmine\network\mcpe\protocol\types\PlayerMovementType;
use pocketmine\network\mcpe\protocol\types\PlayerPermissions;
use pocketmine\network\mcpe\protocol\types\SpawnSettings;
use function count;

class StartGamePacket extends DataPacket{
	public const NETWORK_ID = ProtocolInfo::START_GAME_PACKET;

	/** @var int */
	public $entityUniqueId;
	/** @var int */
	public $entityRuntimeId;
	/** @var int */
	public $playerGamemode;

	/** @var Vector3 */
	public $playerPosition;

	/** @var float */
	public $pitch;
	/** @var float */
	public $yaw;

	/** @var int */
	public $seed;
	/** @var SpawnSettings */
	public $spawnSettings;
	/** @var int */
	public $generator = GeneratorType::OVERWORLD;
	/** @var int */
	public $worldGamemode;
	/** @var int */
	public $difficulty;
	/** @var int */
	public $spawnX;
	/** @var int */
	public $spawnY;
	/** @var int */
	public $spawnZ;
	/** @var bool */
	public $hasAchievementsDisabled = true;
	/** @var int */
	public $time = -1;
	/** @var int */
	public $eduEditionOffer = EducationEditionOffer::NONE;
	/** @var bool */
	public $hasEduFeaturesEnabled = false;
	/** @var string */
	public $eduProductUUID = "";
	/** @var float */
	public $rainLevel;
	/** @var float */
	public $lightningLevel;
	/** @var bool */
	public $hasConfirmedPlatformLockedContent = false;
	/** @var bool */
	public $isMultiplayerGame = true;
	/** @var bool */
	public $hasLANBroadcast = true;
	/** @var int */
	public $xboxLiveBroadcastMode = MultiplayerGameVisibility::PUBLIC;
	/** @var int */
	public $platformBroadcastMode = MultiplayerGameVisibility::PUBLIC;
	/** @var bool */
	public $commandsEnabled;
	/** @var bool */
	public $isTexturePacksRequired = true;
	/**
	 * @var mixed[][]
	 * @phpstan-var array<string, array{0: int, 1: bool|int|float}>
	 */
	public $gameRules = [ //TODO: implement this
		"naturalregeneration" => [GameRuleType::BOOL, false] //Hack for client side regeneration
	];
	/** @var Experiments */
	public $experiments;
	/** @var bool */
	public $hasBonusChestEnabled = false;
	/** @var bool */
	public $hasStartWithMapEnabled = false;
	/** @var int */
	public $defaultPlayerPermission = PlayerPermissions::MEMBER; //TODO

	/** @var int */
	public $serverChunkTickRadius = 4; //TODO (leave as default for now)

	/** @var bool */
	public $hasLockedBehaviorPack = false;
	/** @var bool */
	public $hasLockedResourcePack = false;
	/** @var bool */
	public $isFromLockedWorldTemplate = false;
	/** @var bool */
	public $useMsaGamertagsOnly = false;
	/** @var bool */
	public $isFromWorldTemplate = false;
	/** @var bool */
	public $isWorldTemplateOptionLocked = false;
	/** @var bool */
	public $onlySpawnV1Villagers = false;
	/** @var string */
	public $vanillaVersion = ProtocolInfo::MINECRAFT_VERSION_NETWORK;
	/** @var int */
	public $limitedWorldWidth = 0;
	/** @var int */
	public $limitedWorldLength = 0;
	/** @var bool */
	public $isNewNether = true;
	/** @var bool|null */
	public $experimentalGameplayOverride = null;

	/** @var string */
	public $levelId = ""; //base64 string, usually the same as world folder name in vanilla
	/** @var string */
	public $worldName;
	/** @var string */
	public $premiumWorldTemplateId = "";
	/** @var bool */
	public $isTrial = false;
	/** @var int */
	public $playerMovementType = PlayerMovementType::LEGACY;
	/** @var int */
	public $currentTick = 0; //only used if isTrial is true
	/** @var int */
	public $enchantmentSeed = 0;
	/** @var string */
	public $multiplayerCorrelationId = ""; //TODO: this should be filled with a UUID of some sort

	/**
	 * @var BlockPaletteEntry[]
	 * @phpstan-var list<BlockPaletteEntry>
	 */
	public $blockPalette = [];

	/**
	 * @var ItemTypeEntry[]
	 * @phpstan-var list<ItemTypeEntry>
	 */
	public $itemTable;
	/** @var bool */
	public $enableNewInventorySystem = false; //TODO

	protected function decodePayload(){
		$this->entityUniqueId = $this->getEntityUniqueId();
		$this->entityRuntimeId = $this->getEntityRuntimeId();
		$this->playerGamemode = $this->getVarInt();

		$this->playerPosition = $this->getVector3();

		$this->pitch = ((\unpack("g", $this->get(4))[1]));
		$this->yaw = ((\unpack("g", $this->get(4))[1]));

		//Level settings
		$this->seed = $this->getVarInt();
		$this->spawnSettings = SpawnSettings::read($this);
		$this->generator = $this->getVarInt();
		$this->worldGamemode = $this->getVarInt();
		$this->difficulty = $this->getVarInt();
		$this->getBlockPosition($this->spawnX, $this->spawnY, $this->spawnZ);
		$this->hasAchievementsDisabled = (($this->get(1) !== "\x00"));
		$this->time = $this->getVarInt();
		$this->eduEditionOffer = $this->getVarInt();
		$this->hasEduFeaturesEnabled = (($this->get(1) !== "\x00"));
		$this->eduProductUUID = $this->getString();
		$this->rainLevel = ((\unpack("g", $this->get(4))[1]));
		$this->lightningLevel = ((\unpack("g", $this->get(4))[1]));
		$this->hasConfirmedPlatformLockedContent = (($this->get(1) !== "\x00"));
		$this->isMultiplayerGame = (($this->get(1) !== "\x00"));
		$this->hasLANBroadcast = (($this->get(1) !== "\x00"));
		$this->xboxLiveBroadcastMode = $this->getVarInt();
		$this->platformBroadcastMode = $this->getVarInt();
		$this->commandsEnabled = (($this->get(1) !== "\x00"));
		$this->isTexturePacksRequired = (($this->get(1) !== "\x00"));
		$this->gameRules = $this->getGameRules();
		$this->experiments = Experiments::read($this);
		$this->hasBonusChestEnabled = (($this->get(1) !== "\x00"));
		$this->hasStartWithMapEnabled = (($this->get(1) !== "\x00"));
		$this->defaultPlayerPermission = $this->getVarInt();
		$this->serverChunkTickRadius = ((\unpack("V", $this->get(4))[1] << 32 >> 32));
		$this->hasLockedBehaviorPack = (($this->get(1) !== "\x00"));
		$this->hasLockedResourcePack = (($this->get(1) !== "\x00"));
		$this->isFromLockedWorldTemplate = (($this->get(1) !== "\x00"));
		$this->useMsaGamertagsOnly = (($this->get(1) !== "\x00"));
		$this->isFromWorldTemplate = (($this->get(1) !== "\x00"));
		$this->isWorldTemplateOptionLocked = (($this->get(1) !== "\x00"));
		$this->onlySpawnV1Villagers = (($this->get(1) !== "\x00"));
		$this->vanillaVersion = $this->getString();
		$this->limitedWorldWidth = ((\unpack("V", $this->get(4))[1] << 32 >> 32));
		$this->limitedWorldLength = ((\unpack("V", $this->get(4))[1] << 32 >> 32));
		$this->isNewNether = (($this->get(1) !== "\x00"));
		if((($this->get(1) !== "\x00"))){
			$this->experimentalGameplayOverride = (($this->get(1) !== "\x00"));
		}else{
			$this->experimentalGameplayOverride = null;
		}

		$this->levelId = $this->getString();
		$this->worldName = $this->getString();
		$this->premiumWorldTemplateId = $this->getString();
		$this->isTrial = (($this->get(1) !== "\x00"));
		$this->playerMovementType = $this->getVarInt();
		$this->currentTick = (Binary::readLLong($this->get(8)));

		$this->enchantmentSeed = $this->getVarInt();

		$this->blockPalette = [];
		for($i = 0, $len = $this->getUnsignedVarInt(); $i < $len; ++$i){
			$blockName = $this->getString();
			$state = $this->getNbtCompoundRoot();
			$this->blockPalette[] = new BlockPaletteEntry($blockName, $state);
		}

		$this->itemTable = [];
		for($i = 0, $count = $this->getUnsignedVarInt(); $i < $count; ++$i){
			$stringId = $this->getString();
			$numericId = ((\unpack("v", $this->get(2))[1] << 48 >> 48));
			$isComponentBased = (($this->get(1) !== "\x00"));

			$this->itemTable[] = new ItemTypeEntry($stringId, $numericId, $isComponentBased);
		}

		$this->multiplayerCorrelationId = $this->getString();
		$this->enableNewInventorySystem = (($this->get(1) !== "\x00"));
	}

	protected function encodePayload(){
		$this->putEntityUniqueId($this->entityUniqueId);
		$this->putEntityRuntimeId($this->entityRuntimeId);
		$this->putVarInt($this->playerGamemode);

		$this->putVector3($this->playerPosition);

		($this->buffer .= (\pack("g", $this->pitch)));
		($this->buffer .= (\pack("g", $this->yaw)));

		//Level settings
		$this->putVarInt($this->seed);
		$this->spawnSettings->write($this);
		$this->putVarInt($this->generator);
		$this->putVarInt($this->worldGamemode);
		$this->putVarInt($this->difficulty);
		$this->putBlockPosition($this->spawnX, $this->spawnY, $this->spawnZ);
		($this->buffer .= ($this->hasAchievementsDisabled ? "\x01" : "\x00"));
		$this->putVarInt($this->time);
		$this->putVarInt($this->eduEditionOffer);
		($this->buffer .= ($this->hasEduFeaturesEnabled ? "\x01" : "\x00"));
		$this->putString($this->eduProductUUID);
		($this->buffer .= (\pack("g", $this->rainLevel)));
		($this->buffer .= (\pack("g", $this->lightningLevel)));
		($this->buffer .= ($this->hasConfirmedPlatformLockedContent ? "\x01" : "\x00"));
		($this->buffer .= ($this->isMultiplayerGame ? "\x01" : "\x00"));
		($this->buffer .= ($this->hasLANBroadcast ? "\x01" : "\x00"));
		$this->putVarInt($this->xboxLiveBroadcastMode);
		$this->putVarInt($this->platformBroadcastMode);
		($this->buffer .= ($this->commandsEnabled ? "\x01" : "\x00"));
		($this->buffer .= ($this->isTexturePacksRequired ? "\x01" : "\x00"));
		$this->putGameRules($this->gameRules);
		$this->experiments->write($this);
		($this->buffer .= ($this->hasBonusChestEnabled ? "\x01" : "\x00"));
		($this->buffer .= ($this->hasStartWithMapEnabled ? "\x01" : "\x00"));
		$this->putVarInt($this->defaultPlayerPermission);
		($this->buffer .= (\pack("V", $this->serverChunkTickRadius)));
		($this->buffer .= ($this->hasLockedBehaviorPack ? "\x01" : "\x00"));
		($this->buffer .= ($this->hasLockedResourcePack ? "\x01" : "\x00"));
		($this->buffer .= ($this->isFromLockedWorldTemplate ? "\x01" : "\x00"));
		($this->buffer .= ($this->useMsaGamertagsOnly ? "\x01" : "\x00"));
		($this->buffer .= ($this->isFromWorldTemplate ? "\x01" : "\x00"));
		($this->buffer .= ($this->isWorldTemplateOptionLocked ? "\x01" : "\x00"));
		($this->buffer .= ($this->onlySpawnV1Villagers ? "\x01" : "\x00"));
		$this->putString($this->vanillaVersion);
		($this->buffer .= (\pack("V", $this->limitedWorldWidth)));
		($this->buffer .= (\pack("V", $this->limitedWorldLength)));
		($this->buffer .= ($this->isNewNether ? "\x01" : "\x00"));
		($this->buffer .= ($this->experimentalGameplayOverride !== null ? "\x01" : "\x00"));
		if($this->experimentalGameplayOverride !== null){
			($this->buffer .= ($this->experimentalGameplayOverride ? "\x01" : "\x00"));
		}

		$this->putString($this->levelId);
		$this->putString($this->worldName);
		$this->putString($this->premiumWorldTemplateId);
		($this->buffer .= ($this->isTrial ? "\x01" : "\x00"));
		$this->putVarInt($this->playerMovementType);
		($this->buffer .= (\pack("VV", $this->currentTick & 0xFFFFFFFF, $this->currentTick >> 32)));

		$this->putVarInt($this->enchantmentSeed);

		$this->putUnsignedVarInt(count($this->blockPalette));
		$nbtWriter = new NetworkLittleEndianNBTStream();
		foreach($this->blockPalette as $entry){
			$this->putString($entry->getName());
			($this->buffer .= $nbtWriter->write($entry->getStates()));
		}
		$this->putUnsignedVarInt(count($this->itemTable));
		foreach($this->itemTable as $entry){
			$this->putString($entry->getStringId());
			($this->buffer .= (\pack("v", $entry->getNumericId())));
			($this->buffer .= ($entry->isComponentBased() ? "\x01" : "\x00"));
		}

		$this->putString($this->multiplayerCorrelationId);
		($this->buffer .= ($this->enableNewInventorySystem ? "\x01" : "\x00"));
	}

	public function handle(NetworkSession $session) : bool{
		return $session->handleStartGame($this);
	}
}
