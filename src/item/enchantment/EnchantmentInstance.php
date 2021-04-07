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

namespace pocketmine\item\enchantment;

/**
 * Container for enchantment data applied to items.
 *
 * Note: This class is assumed to be immutable. Consider this before making alterations.
 */
final class EnchantmentInstance{
	/** @var Enchantment */
	private $enchantment;
	/** @var int */
	private $level;

	/**
	 * EnchantmentInstance constructor.
	 *
	 * @param Enchantment $enchantment Enchantment type
	 * @param int         $level Level of enchantment
	 */
	public function __construct(Enchantment $enchantment, int $level = 1){
		$this->enchantment = $enchantment;
		$this->level = $level;
	}

	/**
	 * Returns the type of this enchantment.
	 */
	public function getType() : Enchantment{
		return $this->enchantment;
	}

	/**
	 * Returns the runtime type identifier of this enchantment instance.
	 * WARNING: DO NOT STORE THIS IDENTIFIER - IT MAY CHANGE AFTER SERVER RESTART
	 */
	public function getRuntimeId() : int{
		return $this->enchantment->getRuntimeId();
	}

	/**
	 * Returns the level of the enchantment.
	 */
	public function getLevel() : int{
		return $this->level;
	}
}
