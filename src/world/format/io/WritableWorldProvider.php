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

use pocketmine\world\format\Chunk;
use pocketmine\world\generator\Generator;

interface WritableWorldProvider extends WorldProvider{
	/**
	 * Generate the needed files in the path given
	 *
	 * @param mixed[] $options
	 * @phpstan-param class-string<Generator> $generator
	 * @phpstan-param array<string, mixed>    $options
	 */
	public static function generate(string $path, string $name, int $seed, string $generator, array $options = []) : void;

	/**
	 * Saves a chunk (usually to disk).
	 */
	public function saveChunk(int $chunkX, int $chunkZ, Chunk $chunk) : void;
}
