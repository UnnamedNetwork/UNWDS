<?php

declare(strict_types=1);

namespace pocketmine\inventory;

/**
 * Class PlayerCursorInventory
 * @package pocketmine\inventory
 */
class PlayerCursorInventory extends PlayerUIComponent {

    /**
     * PlayerCursorInventory constructor.
     * @param PlayerUIInventory $ui
     */
    public function __construct(PlayerUIInventory $ui) {
        parent::__construct($ui, 0, 1);
    }
}