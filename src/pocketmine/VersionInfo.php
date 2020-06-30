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

namespace pocketmine;

use function defined;

// composer autoload doesn't use require_once and also pthreads can inherit things
// TODO: drop this file and use a final class with constants
if(defined('pocketmine\_VERSION_INFO_INCLUDED')){
	return;
}
const _VERSION_INFO_INCLUDED = true;

# This mask will bypass Spoon
const NAME = "PocketMine-MP";
const BASE_VERSION= "3.11.0" # UNWDS based on this pmmp version, change it can... I dont know :)
const BUILD_NUMBER = 0;
const IS_DEVELOPMENT_BUILD = true;

# Real face XD
const DISTRO_NAME= "UNWDS";
const DISTRO_VERSION = "2.0.0.1";
const DISTRO_BUILD = "300620.2001"

# Don't use this when this is not set to "true", bcuz that mayn't run
const IS_BUILD_SUCCESSFULLY = false;
