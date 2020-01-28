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

namespace pocketmine\entity;

use pocketmine\entity\data\SkinAnimation;
use pocketmine\nbt\tag\ByteArrayTag;
use pocketmine\nbt\tag\ByteTag;
use pocketmine\nbt\tag\CompoundTag;
use pocketmine\nbt\tag\IntTag;
use pocketmine\nbt\tag\StringTag;
use pocketmine\network\mcpe\protocol\ProtocolInfo;
use pocketmine\utils\SerializedImage;
use function strlen;
use const pocketmine\RESOURCE_PATH;

class Skin {

    public const DEFAULT_SKIN_RESOURCE_PATCH = '{"geometry" : {"default" : "geometry.humanoid.custom"}}';
    public const DEFAULT_SKIN_GEOMETRY_NAME = "geometry.humanoid.custom";

	public const ACCEPTED_SKIN_SIZES = [
		64 * 32 * 4,
		64 * 64 * 4,
        128 * 64 * 4,
		128 * 128 * 4
	];

	/** @var Skin[] $defaultSkins */
	public static $defaultSkins = [];

	/** @var int $version */
	public $version = ProtocolInfo::PROTOCOL_1_12;

	/** @var string $skinId*/
	private $skinId = "Standard_Custom";
	/** @var string $skinResourcePatch */
	private $skinResourcePatch = self::DEFAULT_SKIN_RESOURCE_PATCH; // old geometry name
	/** @var SerializedImage */
	private $skinData;
	/** @var SkinAnimation[] $animations */
	public $animations = [];
	/** @var string $animationData */
    private $animationData = "";
	/** @var string $capeId */
	private $capeId = "";
	/** @var SerializedImage $capeData */
	private $capeData;
	/** @var string $geometryData */
	private $geometryData = "";
	/** @var bool $isPremium */
	private $isPremium = false;
	/** @var bool $isPersona */
	private $isPersona = false;
	/** @var bool $capeOnClassic */
	private $capeOnClassic = false;

	public static function init() {
	    foreach (glob(RESOURCE_PATH . "/skins/*.dat") as $file) {
	        $skin = new Skin();
	        $skin->setSkinData(SerializedImage::fromLegacy(base64_decode(file_get_contents($file))));
	        self::$defaultSkins[basename($file, ".dat")] = $skin;
        }
    }

    /**
     * @return Skin
     */
    public static function getRandomSkin(): Skin {
	    return self::$defaultSkins[array_rand(self::$defaultSkins, 1)];
    }

	/**
	 * @deprecated
	 * @return bool
	 */
	public function isValid() : bool{
		return true;
	}

    /**
     * @param string $skinId
     */
    public function setSkinId(string $skinId): void {
        $this->skinId = $skinId;
    }

	/**
	 * @return string
	 */
	public function getSkinId() : string{
		return $this->skinId;
	}

    /**
     * @param string $skinResourcePatch
     */
    public function setSkinResourcePatch(?string $skinResourcePatch = null): void {
        if($skinResourcePatch === null || strlen(trim($skinResourcePatch)) == 0) {
            $this->skinResourcePatch = self::getGeometryCustomConstant();
            return;
        }
        $this->skinResourcePatch = $skinResourcePatch;
    }

    /**
     * @return string
     */
    public function getSkinResourcePatch(): string {
        return $this->skinResourcePatch;
    }

    /**
     * @return string
     */
    public function getSkinGeometryName(): string {
        try {
            return (json_decode($this->getSkinResourcePatch()))->geometry->default;
        }
        catch (\Exception $e) {
            return self::DEFAULT_SKIN_GEOMETRY_NAME;
        }
    }

    /**
     * @param SerializedImage|null $skinData
     */
    public function setSkinData(SerializedImage $skinData = null): void {
        $this->skinData = $skinData;
    }

    /**
     * @return SerializedImage
     */
	public function getSkinData(): SerializedImage {
		return $this->skinData === null ? SerializedImage::createEmpty() : $this->skinData;
	}

    /**
     * @param SerializedImage $capeData
     */
    public function setCapeData(SerializedImage $capeData): void {
        $this->capeData = $capeData;
    }

    /**
     * @return SerializedImage
     */
	public function getCapeData(): SerializedImage {
		return $this->capeData === null ? SerializedImage::createEmpty() : $this->capeData;
	}

    /**
     * @param string $capeId
     */
    public function setCapeId(?string $capeId = null): void {
        if($capeId === null || strlen(trim($capeId)) == 0) {
            $this->capeId = "";
            return;
        }
        $this->capeId = $capeId;
    }

    /**
     * @return string
     */
    public function getCapeId(): string {
        return $this->capeId;
    }

    /**
     * @param string $geometryData
     */
    public function setGeometryData(string $geometryData): void {
        $this->geometryData = $geometryData;
    }

	/**
	 * @return string
	 */
	public function getGeometryData(): string{
		return (string)$this->geometryData;
	}

    /**
     * @param string $animationData
     */
    public function setAnimationData(string $animationData): void {
        $this->animationData = $animationData;
    }

    /**
     * @return string
     */
    public function getAnimationData(): string {
        return (string)$this->animationData;
    }

    /**
     * @param bool $isPremium
     */
    public function setPremium(bool $isPremium = false): void {
        $this->isPremium = $isPremium;
    }

    /**
     * @return bool
     */
    public function isPremium(): bool {
        return $this->isPremium;
    }

    /**
     * @param bool $isPersona
     */
    public function setPersona(bool $isPersona = false): void {
        $this->isPersona = $isPersona;
    }

    /**
     * @return bool
     */
    public function isPersona(): bool {
        return $this->isPersona;
    }

    /**
     * @param bool $capeOnClassic
     */
    public function setCapeOnClassic(bool $capeOnClassic): void {
        $this->capeOnClassic = $capeOnClassic;
    }

    /**
     * @return bool
     */
    public function isCapeOnClassic(): bool{
        return $this->capeOnClassic;
    }

    /**
     * @return string
     */
    public function getFullSkinId(): string {
        return $this->getSkinId() . "_" . $this->getCapeId();
    }

    public function serializeNBT(): CompoundTag {
        return new CompoundTag("Skin", [
            new StringTag("Name", $this->getSkinId()),
            new ByteArrayTag("Data", $this->getSkinData()->data),
            new IntTag("SkinWidth", $this->getSkinData()->width), // new
            new IntTag("SkinHeight", $this->getSkinData()->height), // new
            new StringTag("CapeId", $this->getCapeId()), // new
            new ByteArrayTag("CapeData", $this->getCapeData()->data),
            new IntTag("CapeWidth", $this->getCapeData()->width), // new
            new IntTag("CapeHeight", $this->getCapeData()->height), // new
            new StringTag("GeometryName", $this->getSkinResourcePatch()),
            new ByteArrayTag("GeometryData", $this->getGeometryData()),
            new ByteTag("Premium", (int)$this->isPremium()), // new
            new ByteTag("Persona", (int)$this->isPersona()), // new
            new ByteTag("CapeOnClassic", (int)$this->isCapeOnClassic()) // new
        ]);
    }

    /**
     * @param CompoundTag $compound
     * @return Skin
     */
    public static function deserializeNBT(CompoundTag $compound): Skin {
        $skin = new Skin();
        $skin->setSkinId($compound->getString("Name"));

        if($compound->offsetExists("SkinWidth") && $compound->offsetExists("SkinHeight")) {
            $skin->setSkinData(new SerializedImage($compound->getInt("SkinWidth"), $compound->getInt("SkinHeight"), $compound->getByteArray("Data")));
        }
        else {
            $skin->setSkinData(SerializedImage::fromLegacy($compound->getByteArray("Data")));
        }

        if($compound->offsetExists("CapeId"))
            $skin->setCapeId($compound->getString("CapeId"));

        if($compound->offsetExists("CapeWidth") && $compound->offsetExists("CapeHeight")) {
            $skin->setSkinData(new SerializedImage($compound->getInt("CapeWidth"), $compound->getInt("CapeHeight"), $compound->getByteArray("CapeData")));
        }
        else {
            $skin->setSkinData(SerializedImage::fromLegacy($compound->getByteArray("CapeData")));
        }

        $skin->setSkinResourcePatch($compound->getString("GeometryName"));
        $skin->setGeometryData($compound->getByteArray("GeometryData"));

        if($compound->offsetExists("Premium")) {
            $skin->setPremium((bool)$compound->getByte("Premium"));
        }
        if($compound->offsetExists("Persona")) {
            $skin->setPersona((bool)$compound->getByte("Persona"));
        }
        if($compound->offsetExists("CapeOnClassic")) {
            $skin->setCapeOnClassic((bool)$compound->getByte("CapeOnClassic"));
        }

        return $skin;
    }


	/**
	 * Hack to cut down on network overhead due to skins, by un-pretty-printing geometry JSON.
	 *
	 * Mojang, some stupid reason, send every single model for every single skin in the selected skin-pack.
	 * Not only that, they are pretty-printed.
	 * TODO: find out what model crap can be safely dropped from the packet (unless it gets fixed first)
	 */
	public function debloatGeometryData(): void {

	}

    /**
     * @param string $geometryName
     * @return string
     */
	private static function convertLegacyGeometryName(string $geometryName) {
        return '{"geometry" : {"default" : "'.$geometryName.'"}}';
    }

    /**
     * @param bool $slim
     * @return string
     */
    public static final function getGeometryCustomConstant(bool $slim = false) {
        return self::convertLegacyGeometryName("geometry.humanoid.custom" . ($slim ? "Slim" : ""));
    }

    /**
     * @return Skin
     */
    public static function createEmpty() {
        $skin = new Skin();
        $skin->setSkinData(SerializedImage::fromLegacy(str_repeat("\x00", 8192)));
        $skin->setSkinId("Standard_Custom");
        $skin->setCapeData(SerializedImage::createEmpty());
        return $skin;
    }

    /**
     * Source: Steafast2
     *
     * @param $skinGeometryData
     * @return false|string
     */
    public static function prepareGeometryDataForOld($skinGeometryData) {
        if (!empty($skinGeometryData)) {
            if (($tempData = @json_decode($skinGeometryData, true))) {
                unset($tempData["format_version"]);
                return json_encode($tempData);
            }
        }
        return $skinGeometryData;
    }


}
