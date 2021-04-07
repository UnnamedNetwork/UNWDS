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

use pocketmine\network\mcpe\protocol\serializer\PacketSerializer;

final class IntGameRule extends GameRule{

	/** @var int */
	private $value;

	public function __construct(int $value){
		$this->value = $value;
	}

	public function getType() : int{
		return GameRuleType::INT;
	}

	public function getValue() : int{
		return $this->value;
	}

	public function encode(PacketSerializer $out) : void{
		$out->putUnsignedVarInt($this->value);
	}

	public static function decode(PacketSerializer $in) : self{
		return new self($in->getUnsignedVarInt());
	}
}
