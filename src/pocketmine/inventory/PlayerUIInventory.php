<?php

declare(strict_types=1);

namespace pocketmine\inventory;

use pocketmine\network\mcpe\protocol\InventorySlotPacket;
use pocketmine\network\mcpe\protocol\types\ContainerIds;
use pocketmine\Player;

/**
 * Class PlayerUIInventory
 * @package pocketmine\inventory
 */
class PlayerUIInventory extends BaseInventory {

    /** @var Player $player */
    protected $player;
    /** @var PlayerCursorInventory $cursorInventory */
    private $cursorInventory;
    /** @var CraftingGrid $craftingGrid */
    private $craftingGrid;
    /** @var BigCraftingGrid $bigCraftingGrid */
    private $bigCraftingGrid;

    /**
     * PlayerUIInventory constructor.
     * @param Player $player
     */
    public function __construct(Player $player) {
        parent::__construct([], $this->getDefaultSize(), $this->getName());

        $this->player = $player;

        $this->cursorInventory = new PlayerCursorInventory($this);
        $this->craftingGrid = new CraftingGrid($this);
        $this->bigCraftingGrid = new BigCraftingGrid($this);
    }

    /**
     * @return CraftingGrid
     */
    public function getCraftingGrid(): CraftingGrid {
        return $this->craftingGrid;
    }

    /**
     * @return BigCraftingGrid
     */
    public function getBigCraftingGrid(): BigCraftingGrid {
        return $this->bigCraftingGrid;
    }

    /**
     * @return PlayerCursorInventory
     */
    public function getCursorInventory(): PlayerCursorInventory {
        return $this->cursorInventory;
    }

    public function sendSlot(int $index, $target): void {
        $pk = new InventorySlotPacket();
        $pk->inventorySlot = $index;
        $pk->item = $this->getItem($index);

        if(!is_array($target)) {
            $target = [$target];
        }

        /** @var Player $player */
        foreach ($target as $player) {
            if ($player->getId() === $this->getHolder()->getId()) {
                $pk->windowId = ContainerIds::CURSOR;
                $player->dataPacket($pk);
            }
            else {
                if (($id = $player->getWindowId($this)) == ContainerIds::NONE) {
                    $this->close($player);
                    continue;
                }

                $pk->windowId = $id;
                $player->dataPacket($pk);
            }
        }
    }

    /**
     * @return int
     */
    public function getDefaultSize(): int {
        return 51;
    }

    /**
     * @return string
     */
    public function getName(): string {
        return "UI";
    }

    /**
     * @return Player
     */
    public function getHolder(): Player {
        return $this->player;
    }
}