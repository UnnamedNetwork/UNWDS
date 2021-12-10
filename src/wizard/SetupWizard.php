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

/**
 * Set-up wizard used on the first run
 * Can be disabled with --no-wizard
 */
namespace pocketmine\wizard;

use pocketmine\data\java\GameModeIdMap;
use pocketmine\lang\KnownTranslationFactory;
use pocketmine\lang\Language;
use pocketmine\lang\LanguageNotFoundException;
use pocketmine\player\GameMode;
use pocketmine\utils\Config;
use pocketmine\utils\Internet;
use pocketmine\utils\InternetException;
use pocketmine\utils\Utils;
use pocketmine\VersionInfo;
use Webmozart\PathUtil\Path;
use function fgets;
use function sleep;
use function strtolower;
use function trim;
use const PHP_EOL;
use const STDIN;

class SetupWizard{
	public const DEFAULT_NAME = VersionInfo::DISTRO_NAME . " Server";
	public const DEFAULT_PORT = 19132;
	public const DEFAULT_PLAYERS = 20;

	/** @var Language */
	private $lang;
	/** @var string */
	private $dataPath;

	public function __construct(string $dataPath){
		$this->dataPath = $dataPath;
	}

	public function run() : bool{
		$this->message(VersionInfo::DISTRO_NAME . " set-up wizard");

		try{
			$langs = Language::getLanguageList();
		}catch(LanguageNotFoundException $e){
			$this->error("No language files found, please use provided builds or clone the repository recursively.");
			return false;
		}

		$this->message("Please select a language");
		foreach(Utils::stringifyKeys($langs) as $short => $native){
			$this->writeLine(" $native => $short");
		}

		do{
			$lang = strtolower($this->getInput("Language", "eng"));
			if(!isset($langs[$lang])){
				$this->error("Couldn't find the language");
				$lang = null;
			}
		}while($lang === null);

		$this->lang = new Language($lang);

		$this->message($this->lang->translate(KnownTranslationFactory::language_has_been_selected()));

		if(!$this->showLicense()){
			return false;
		}

		//this has to happen here to prevent user avoiding agreeing to license
		$config = new Config(Path::join($this->dataPath, "server.properties"), Config::PROPERTIES);
		$config->set("language", $lang);
		$config->save();

		if(strtolower($this->getInput($this->lang->translate(KnownTranslationFactory::skip_installer()), "n", "y/N")) === "y"){
			$this->printIpDetails();
			return true;
		}

		$this->writeLine();
		$this->welcome();
		$this->generateBaseConfig();
		$this->generateUserFiles();

		$this->networkFunctions();
		$this->printIpDetails();

		$this->endWizard();

		return true;
	}

	private function showLicense() : bool{
		$this->message($this->lang->translate(KnownTranslationFactory::welcome_to_pocketmine(VersionInfo::DISTRO_NAME)));
		echo <<<LICENSE

  This program is free software: you can redistribute it and/or modify
  it under the terms of the GNU Lesser General Public License as published by
  the Free Software Foundation, either version 3 of the License, or
  (at your option) any later version.

LICENSE;
		$this->writeLine();
		if(strtolower($this->getInput($this->lang->translate(KnownTranslationFactory::accept_license()), "n", "y/N")) !== "y"){
			$this->error($this->lang->translate(KnownTranslationFactory::you_have_to_accept_the_license(VersionInfo::DISTRO_NAME)));
			sleep(5);

			return false;
		}

		return true;
	}

	private function showCompatibilityModeNotes() : bool{
		$this->message($this->lang->translate(KnownTranslationFactory::cmnotes_1(VersionInfo::DISTRO_NAME)));
		echo <<<CMNOTES

  UNWDS is an PocketMine-MP fork (also called spoon). UNWDS didn't touch/change anything related to PocketMine-MP API, 
  but it's still spoon, this WILL NOT get any support for plugins.
  
  In order to begin using this server software you must understand that you will be offered no support for plugins.

  Furthermore, the GitHub issue tracker for some plugins is targeted at vanilla PocketMine only. 
  Any bugs you create which DO NOT affect with vanilla PocketMine will be deleted.

  Make sure you can reproduce bugs in vanilla PocketMine-MP before report it to plugin developers.
  
  If you use UNWDS, you agree to these terms by default. Otherwise, please stop using UNWDS immediately. 

CMNOTES;
		$this->writeLine();
		if(strtolower($this->getInput($this->lang->translate(KnownTranslationFactory::accept_cmnotes()), "n", "y/N")) !== "y"){
			$this->error($this->lang->translate(KnownTranslationFactory::you_have_to_accept_the_cmnotes(VersionInfo::DISTRO_NAME)));
			sleep(5);

			return false;
		}

		return true;
	}

	private function welcome() : void{
		$this->message($this->lang->translate(KnownTranslationFactory::setting_up_server_now()));
		$this->message($this->lang->translate(KnownTranslationFactory::default_values_info()));
		$this->message($this->lang->translate(KnownTranslationFactory::server_properties()));
	}

	private function generateBaseConfig() : void{
		$config = new Config(Path::join($this->dataPath, "server.properties"), Config::PROPERTIES);

		$config->set("motd", ($name = $this->getInput($this->lang->translate(KnownTranslationFactory::name_your_server()), self::DEFAULT_NAME)));
		$config->set("server-name", $name);

		$this->message($this->lang->translate(KnownTranslationFactory::port_warning()));

		do{
			$port = (int) $this->getInput($this->lang->translate(KnownTranslationFactory::server_port()), (string) self::DEFAULT_PORT);
			if($port <= 0 or $port > 65535){
				$this->error($this->lang->translate(KnownTranslationFactory::invalid_port()));
				continue;
			}

			break;
		}while(true);
		$config->set("server-port", $port);

		$this->message($this->lang->translate(KnownTranslationFactory::gamemode_info()));

		do{
			$gamemode = (int) $this->getInput($this->lang->translate(KnownTranslationFactory::default_gamemode()), (string) GameModeIdMap::getInstance()->toId(GameMode::SURVIVAL()));
		}while($gamemode < 0 or $gamemode > 3);
		$config->set("gamemode", $gamemode);

		$config->set("max-players", (int) $this->getInput($this->lang->translate(KnownTranslationFactory::max_players()), (string) self::DEFAULT_PLAYERS));

		$config->save();
	}

	private function generateUserFiles() : void{
		$this->message($this->lang->translate(KnownTranslationFactory::op_info()));

		$op = strtolower($this->getInput($this->lang->translate(KnownTranslationFactory::op_who()), ""));
		if($op === ""){
			$this->error($this->lang->translate(KnownTranslationFactory::op_warning()));
		}else{
			$ops = new Config(Path::join($this->dataPath, "ops.txt"), Config::ENUM);
			$ops->set($op, true);
			$ops->save();
		}

		$this->message($this->lang->translate(KnownTranslationFactory::whitelist_info()));

		$config = new Config(Path::join($this->dataPath, "server.properties"), Config::PROPERTIES);
		if(strtolower($this->getInput($this->lang->translate(KnownTranslationFactory::whitelist_enable()), "n", "y/N")) === "y"){
			$this->error($this->lang->translate(KnownTranslationFactory::whitelist_warning()));
			$config->set("white-list", true);
		}else{
			$config->set("white-list", false);
		}
		$config->save();
	}

	private function networkFunctions() : void{
		$config = new Config(Path::join($this->dataPath, "server.properties"), Config::PROPERTIES);
		$this->error($this->lang->translate(KnownTranslationFactory::query_warning1()));
		$this->error($this->lang->translate(KnownTranslationFactory::query_warning2()));
		if(strtolower($this->getInput($this->lang->translate(KnownTranslationFactory::query_disable()), "n", "y/N")) === "y"){
			$config->set("enable-query", false);
		}else{
			$config->set("enable-query", true);
		}

		$config->save();
	}

	private function printIpDetails() : void{
		$this->message($this->lang->translate(KnownTranslationFactory::ip_get()));

		$externalIP = Internet::getIP();
		if($externalIP === false){
			$externalIP = "unknown (server offline)";
		}
		try{
			$internalIP = Internet::getInternalIP();
		}catch(InternetException $e){
			$internalIP = "unknown (" . $e->getMessage() . ")";
		}

		$this->error($this->lang->translate(KnownTranslationFactory::ip_warning($externalIP, $internalIP)));
		$this->error($this->lang->translate(KnownTranslationFactory::ip_confirm()));
		$this->readLine();
	}

	private function endWizard() : void{
		$this->message($this->lang->translate(KnownTranslationFactory::you_have_finished()));
		$this->message($this->lang->translate(KnownTranslationFactory::pocketmine_plugins()));
		$this->message($this->lang->translate(KnownTranslationFactory::pocketmine_will_start(VersionInfo::DISTRO_NAME)));

		$this->writeLine();
		$this->writeLine();

		sleep(4);
	}

	private function writeLine(string $line = "") : void{
		echo $line . PHP_EOL;
	}

	private function readLine() : string{
		return trim((string) fgets(STDIN));
	}

	private function message(string $message) : void{
		$this->writeLine("[*] " . $message);
	}

	private function error(string $message) : void{
		$this->writeLine("[!] " . $message);
	}

	private function getInput(string $message, string $default = "", string $options = "") : string{
		$message = "[?] " . $message;

		if($options !== "" or $default !== ""){
			$message .= " (" . ($options === "" ? $default : $options) . ")";
		}
		$message .= ": ";

		echo $message;

		$input = $this->readLine();

		return $input === "" ? $default : $input;
	}
}
