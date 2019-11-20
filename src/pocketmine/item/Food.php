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

namespace pocketmine\item;

use pocketmine\entity\Living;
use pocketmine\event\player\PlayerItemConsumeEvent;
use pocketmine\math\Vector3;
use pocketmine\network\mcpe\protocol\CompletedUsingItemPacket;
use pocketmine\network\mcpe\protocol\ProtocolInfo;
use pocketmine\Player;

abstract class Food extends Item implements FoodSource{

	public function requiresHunger() : bool{
		return true;
	}

    /**
     * @param Player $player
     * @param int $usedTicks
     *
     * @return int
     */
	public function completeAction(Player $player, int $usedTicks): int {
        return CompletedUsingItemPacket::ACTION_EAT;
    }

    /**
     * @param Player $player
     * @param int $ticksUsed
     * @return bool
     */
    public function onUse(Player $player, int $ticksUsed): bool {
        if(!($player instanceof Player && $player->getProtocol() >= ProtocolInfo::PROTOCOL_1_13)) {
            return false;
        }

        $event = new PlayerItemConsumeEvent($player, $this);
        $player->getServer()->getPluginManager()->callEvent($event);

        if($event->isCancelled()) {
            return false;
        }

        if($player->getGamemode() === $player::SURVIVAL && $player->consumeObject($this)) {
            $this->pop();
            $player->getInventory()->setItemInHand($this);
            $player->getInventory()->addItem($this->getResidue());
        }

        foreach ($this->getAdditionalEffects() as $effect) {
            $player->addEffect($effect);
        }

        return true;
    }


    /**
	 * @return Item
	 */
	public function getResidue(){
		return ItemFactory::get(Item::AIR, 0, 0);
	}

	public function getAdditionalEffects() : array{
		return [];
	}

    /**
     * @param Player $player
     * @param Vector3 $directionVector
     *
     * @return bool
     */
	public function onClickAir(Player $player, Vector3 $directionVector): bool {
        return $player->getFood() !== $player->getMaxFood();
    }

    public function onConsume(Living $consumer){

	}
}
