<?php

declare(strict_types=1);

namespace pocketmine\inventory;


use pocketmine\item\Item;

/**
 * Class CraftingGrid
 * @package pocketmine\inventory
 */
class BigCraftingGrid extends PlayerUIComponent implements CraftingGridInterface {

    private const GRID_WIDTH = 3;

    /** @var int|null */
    private $startX;
    /** @var int|null */
    private $xLen;
    /** @var int|null */
    private $startY;
    /** @var int|null */
    private $yLen;

    /**
     * CraftingGrid constructor.
     * @param PlayerUIInventory $inventory
     * @param int $size
     * @param int $offset
     */
    public function __construct(PlayerUIInventory $inventory, int $size = 32, int $offset = 9) {
        parent::__construct($inventory, $size, $offset);
    }

    private function seekRecipeBounds() : void{
        $minX = PHP_INT_MAX;
        $maxX = 0;

        $minY = PHP_INT_MAX;
        $maxY = 0;

        $empty = true;

        for($y = 0; $y < self::GRID_WIDTH; ++$y){
            for($x = 0; $x < self::GRID_WIDTH; ++$x){
                if(!$this->isSlotEmpty($y * self::GRID_WIDTH + $x)){
                    $minX = min($minX, $x);
                    $maxX = max($maxX, $x);

                    $minY = min($minY, $y);
                    $maxY = max($maxY, $y);

                    $empty = false;
                }
            }
        }

        if(!$empty){
            $this->startX = $minX;
            $this->xLen = $maxX - $minX + 1;
            $this->startY = $minY;
            $this->yLen = $maxY - $minY + 1;
        }else{
            $this->startX = $this->xLen = $this->startY = $this->yLen = null;
        }
    }

    /**
     * Returns the item at offset x,y, offset by where the starts of the recipe rectangle are.
     *
     * @param int $x
     * @param int $y
     *
     * @return Item
     */
    public function getIngredient(int $x, int $y) : Item{
        if($this->startX !== null and $this->startY !== null){
            return $this->getItem(($y + $this->startY) * self::GRID_WIDTH + ($x + $this->startX));
        }

        throw new \InvalidStateException("No ingredients found in grid");
    }

    /**
     * Returns the width of the recipe we're trying to craft, based on items currently in the grid.
     *
     * @return int
     */
    public function getRecipeWidth() : int{
        return $this->xLen ?? 0;
    }

    /**
     * Returns the height of the recipe we're trying to craft, based on items currently in the grid.
     * @return int
     */
    public function getRecipeHeight() : int{
        return $this->yLen ?? 0;
    }
}
