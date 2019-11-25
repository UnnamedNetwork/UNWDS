<?php

declare(strict_types=1);

namespace pocketmine\network\mcpe\protocol\types;

use pocketmine\nbt\BigEndianNBTStream;
use pocketmine\nbt\NetworkLittleEndianNBTStream;
use pocketmine\nbt\tag\NamedTag;
use pocketmine\network\mcpe\protocol\ProtocolInfo;

/**
 * Class BiomeDefinitions
 * @package pocketmine\network\mcpe\protocol\types
 */
final class BiomeDefinitions {

    /** @var BiomeDefinitions[] $biomeCache */
    private static $biomeData = [];

    /** @var NamedTag[] $nbt */
    private $nbt;

    /** @var string $hardcodedBlob */
    private $hardcodedBlob;

    /**
     * BiomeDefinitions constructor.
     * @param string $file
     */
    public function __construct(string $file) {
        $stream = new BigEndianNBTStream();
        $this->nbt = $stream->read(file_get_contents($file));
        $this->hardcodedBlob = (new NetworkLittleEndianNBTStream())->write($this->nbt);
    }

    /**
     * @return array
     */
    public function getNameTags(): array {
        return $this->nbt;
    }

    /**
     * @return string
     */
    public function getHardcodedBlob(): string {
        return $this->hardcodedBlob;
    }

    public static function init() {
        self::$biomeData[ProtocolInfo::PROTOCOL_1_12] = new BiomeDefinitions(\pocketmine\RESOURCE_PATH . "/vanilla/biomes_1_12.dat");
        self::$biomeData[ProtocolInfo::PROTOCOL_1_13] = new BiomeDefinitions(\pocketmine\RESOURCE_PATH . "/vanilla/biomes_1_13.dat");
        self::$biomeData[ProtocolInfo::PROTOCOL_1_14] = new BiomeDefinitions(\pocketmine\RESOURCE_PATH . "/vanilla/biomes_1_13.dat");
    }

    /**
     * @param int $protocol
     * @return BiomeDefinitions
     */
    public static function getBiomeData(int $protocol) {
        return self::$biomeData[$protocol];
    }
}