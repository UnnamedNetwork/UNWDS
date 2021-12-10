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

namespace pocketmine\player;

use pocketmine\entity\Skin;
use pocketmine\utils\TextFormat;
use Ramsey\Uuid\UuidInterface;

/**
 * Encapsulates data needed to create a player.
 */
class PlayerInfo{

	/** @var string */
	private $username;
	/** @var UuidInterface */
	private $uuid;
	/** @var Skin */
	private $skin;
	/** @var string */
	private $locale;
	/**
	 * @var mixed[]
	 * @phpstan-var array<string, mixed>
	 */
	private $extraData;

	/**
	 * @param mixed[] $extraData
	 * @phpstan-param array<string, mixed> $extraData
	 */
	public function __construct(string $username, UuidInterface $uuid, Skin $skin, string $locale, array $extraData = []){
		$this->username = TextFormat::clean($username);
		$this->uuid = $uuid;
		$this->skin = $skin;
		$this->locale = $locale;
		$this->extraData = $extraData;
	}

	public function getUsername() : string{
		return $this->username;
	}

	public function getUuid() : UuidInterface{
		return $this->uuid;
	}

	public function getSkin() : Skin{
		return $this->skin;
	}

	public function getLocale() : string{
		return $this->locale;
	}

	/**
	 * @return mixed[]
	 * @phpstan-return array<string, mixed>
	 */
	public function getExtraData() : array{
		return $this->extraData;
	}
}
