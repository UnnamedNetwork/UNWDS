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

namespace pocketmine\network\mcpe\protocol\types;

use pocketmine\network\mcpe\protocol\types\skin\SkinData;
use Ramsey\Uuid\UuidInterface;

class PlayerListEntry{

	/** @var UuidInterface */
	public $uuid;
	/** @var int */
	public $entityUniqueId;
	/** @var string */
	public $username;
	/** @var SkinData */
	public $skinData;
	/** @var string */
	public $xboxUserId;
	/** @var string */
	public $platformChatId = "";
	/** @var int */
	public $buildPlatform = DeviceOS::UNKNOWN;
	/** @var bool */
	public $isTeacher = false;
	/** @var bool */
	public $isHost = false;

	public static function createRemovalEntry(UuidInterface $uuid) : PlayerListEntry{
		$entry = new PlayerListEntry();
		$entry->uuid = $uuid;

		return $entry;
	}

	public static function createAdditionEntry(UuidInterface $uuid, int $entityUniqueId, string $username, SkinData $skinData, string $xboxUserId = "", string $platformChatId = "", int $buildPlatform = -1, bool $isTeacher = false, bool $isHost = false) : PlayerListEntry{
		$entry = new PlayerListEntry();
		$entry->uuid = $uuid;
		$entry->entityUniqueId = $entityUniqueId;
		$entry->username = $username;
		$entry->skinData = $skinData;
		$entry->xboxUserId = $xboxUserId;
		$entry->platformChatId = $platformChatId;
		$entry->buildPlatform = $buildPlatform;
		$entry->isTeacher = $isTeacher;
		$entry->isHost = $isHost;

		return $entry;
	}
}
