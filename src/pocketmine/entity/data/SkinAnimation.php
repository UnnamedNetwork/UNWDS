<?php

declare(strict_types=1);

namespace pocketmine\entity\data;

use pocketmine\utils\SerializedImage;

/**
 * Class SkinAnimation
 * @package pocketmine\entity\data
 */
class SkinAnimation {

    /** @var SerializedImage $image */
    public $image;
    /** @var float $frames */
    public $frames;
    /** @var int $type */
    public $type;

    /**
     * SkinAnimation constructor.
     * @param SerializedImage $image
     * @param int $type
     * @param float $frames
     */
    public function __construct(SerializedImage $image, int $type, float $frames) {
        $this->image = $image;
        $this->type = $type;
        $this->frames = $frames;
    }
}