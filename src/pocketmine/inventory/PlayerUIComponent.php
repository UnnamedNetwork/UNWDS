<?php

declare(strict_types=1);

namespace pocketmine\inventory;

use pocketmine\item\Item;
use pocketmine\Player;

/**
 * Class PlayerUIComponent
 * @package pocketmine\inventory
 */
class PlayerUIComponent extends BaseInventory {

    /** @var PlayerUIInventory $playerUI */
    private $playerUI;
    /** @var int $size */
    private $size;
    /** @var int $offset */
    private $offset;


    /**
     * PlayerUIComponent constructor.
     * @param PlayerUIInventory $inventory
     * @param int $size
     * @param int $offset
     */
    public function __construct(PlayerUIInventory $inventory, int $size, int $offset) {
        $this->size = $size;
        $this->offset = $offset;
        $this->playerUI = $inventory;
        parent::__construct([], $size, "UI");
    }

    /**
     * @return int
     */
    public function getSize(): int {
        return $this->size;
    }

    /**
     * @return int
     */
    public function getDefaultSize(): int {
        return 64;
    }

    /**
     * @return array
     */
    public function getViewers(): array {
        return $this->playerUI->getViewers();
    }

    /**
     * @return Player
     */
    public function getHolder(): Player {
        return $this->playerUI->getHolder();
    }

    /**
     * @param Player $who
     * @return bool
     */
    public function open(Player $who): bool {
        return false;
    }

    /**
     * @param Player $who
     */
    public function onOpen(Player $who): void {

    }

    /**
     * @param Player $who
     */
    public function close(Player $who): void {

    }

    /**
     * @param Player $who
     */
    public function onClose(Player $who): void {

    }

    /**
     * @param int $index
     * @param Item $before
     * @param bool $send
     */
    public function onSlotChange(int $index, Item $before, bool $send): void {

    }

    /**
     * @param Player|Player[] $target
     */
    public function sendContents($target): void {

    }

    /**
     * @param int $index
     * @param Player|Player[] $target
     */
    public function sendSlot(int $index, $target): void {

    }

    /**
     * @return string
     * @throws UnsupportedOperationException
     */
    public function getName(): string {
        throw new UnsupportedOperationException("Invalid inventory");
    }


}

