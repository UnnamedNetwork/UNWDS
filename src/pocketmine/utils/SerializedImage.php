<?php

declare(strict_types=1);

namespace pocketmine\utils;

/**
 * Class SerializedImage
 * @package pocketmine\utils
 */
class SerializedImage {

    /** @var int $width */
    public $width;
    /** @var int $height */
    public $height;
    /** @var string $data */
    public $data;

    /**
     * SerializedImage constructor.
     * @param int $width
     * @param int $height
     * @param string $data
     */
    public function __construct(int $width, int $height, string $data) {
        $this->width = $width;
        $this->height = $height;
        $this->data = $data;
    }

    /**
     * @param string $data
     * @return SerializedImage
     */
    public static function fromLegacy(string $data): SerializedImage {
        switch (strlen($data)) {
            case 0:
                return self::createEmpty();
            case 64 * 32 * 4:
                return new SerializedImage(64, 32, $data);
            case 64 * 64 * 4:
                return new SerializedImage(64, 64, $data);
            case 128 * 64 * 4:
                return new SerializedImage(128, 64, $data);
            case 128 * 128 * 4:
                return new SerializedImage(128, 128, $data);
        }

        throw new \InvalidArgumentException("Invalid skin size");
    }

    /**
     * @return SerializedImage
     */
    public static function createEmpty(): SerializedImage {
        return new SerializedImage(0, 0, "");
    }
}