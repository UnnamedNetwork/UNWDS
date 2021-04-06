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
// we deleted SpoonMask.php because we just need only 1 file that contain all version info...
if(defined('pocketmine\_VERSION_INFO_INCLUDED')){
	return;
}
const _VERSION_INFO_INCLUDED = true;
// Name section
const NAME = "PocketMine-MP"; // This name will bypass the SpoonDetector and plugins.
const DISTRO_NAME = "UNWDS"; // The name will appear at CLI, /version command, etc... FAKE NAME!
const CODENAME = "Twee";
const BASE_VERSION = "3.18.2"; // Real API version, plugins need corresponding PocketMine-MP base version as API version to run, so we not recommend to change this. 
const DISTRO_VERSION = "2.3.2"; // Like distro name but it's version
const IS_DEVELOPMENT_BUILD = true;
const BUILD_NUMBER = 0333;