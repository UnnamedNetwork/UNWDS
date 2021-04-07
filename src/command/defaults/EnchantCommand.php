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

namespace pocketmine\command\defaults;

use pocketmine\command\CommandSender;
use pocketmine\command\utils\InvalidCommandSyntaxException;
use pocketmine\item\enchantment\EnchantmentInstance;
use pocketmine\item\enchantment\VanillaEnchantments;
use pocketmine\lang\TranslationContainer;
use pocketmine\utils\TextFormat;
use function count;

class EnchantCommand extends VanillaCommand{

	public function __construct(string $name){
		parent::__construct(
			$name,
			"%pocketmine.command.enchant.description",
			"%commands.enchant.usage"
		);
		$this->setPermission("pocketmine.command.enchant");
	}

	public function execute(CommandSender $sender, string $commandLabel, array $args){
		if(!$this->testPermission($sender)){
			return true;
		}

		if(count($args) < 2){
			throw new InvalidCommandSyntaxException();
		}

		$player = $sender->getServer()->getPlayerByPrefix($args[0]);

		if($player === null){
			$sender->sendMessage(new TranslationContainer(TextFormat::RED . "%commands.generic.player.notFound"));
			return true;
		}

		$item = $player->getInventory()->getItemInHand();

		if($item->isNull()){
			$sender->sendMessage(new TranslationContainer("commands.enchant.noItem"));
			return true;
		}

		try{
			$enchantment = VanillaEnchantments::fromString($args[1]);
		}catch(\InvalidArgumentException $e){
			$sender->sendMessage(new TranslationContainer("commands.enchant.notFound", [$args[1]]));
			return true;
		}

		$level = 1;
		if(isset($args[2])){
			$level = $this->getBoundedInt($sender, $args[2], 1, $enchantment->getMaxLevel());
			if($level === null){
				return false;
			}
		}

		$item->addEnchantment(new EnchantmentInstance($enchantment, $level));
		$player->getInventory()->setItemInHand($item);

		self::broadcastCommandMessage($sender, new TranslationContainer("%commands.enchant.success", [$player->getName()]));
		return true;
	}
}
