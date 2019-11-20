<?php

declare(strict_types=1);

namespace pocketmine\inventory;

/**
 * Class BigCraftingGrid
 * @package pocketmine\inventory
 */
class CraftingGrid extends PlayerUIComponent implements CraftingGridInterface {

    /**
     * CraftingGrid constructor.
     * @param PlayerUIInventory $inventory
     * @param int $size
     * @param int $offset
     */
    public function __construct(PlayerUIInventory $inventory, int $size = 28, int $offset = 4) {
        parent::__construct($inventory, $size, $offset);
    }
}
