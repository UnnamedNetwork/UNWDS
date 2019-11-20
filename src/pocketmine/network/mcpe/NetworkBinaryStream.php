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

namespace pocketmine\network\mcpe;

#include <rules/DataPacket.h>

use pocketmine\entity\Attribute;
use pocketmine\entity\data\SkinAnimation;
use pocketmine\entity\Entity;
use pocketmine\entity\Skin;
use pocketmine\item\Durable;
use pocketmine\item\Item;
use pocketmine\item\ItemFactory;
use pocketmine\item\ItemIds;
use pocketmine\math\Vector3;
use pocketmine\nbt\NetworkLittleEndianNBTStream;
use pocketmine\nbt\tag\CompoundTag;
use pocketmine\nbt\tag\IntTag;
use pocketmine\network\mcpe\protocol\types\CommandOriginData;
use pocketmine\network\mcpe\protocol\types\EntityLink;
use pocketmine\network\mcpe\protocol\types\StructureSettings;
use pocketmine\utils\BinaryStream;
use pocketmine\utils\SerializedImage;
use pocketmine\utils\SkinUtils;
use pocketmine\utils\UUID;
use function count;
use function strlen;

class NetworkBinaryStream extends BinaryStream{

	private const DAMAGE_TAG = "Damage"; //TAG_Int
	private const DAMAGE_TAG_CONFLICT_RESOLUTION = "___Damage_ProtocolCollisionResolution___";

	public function getString() : string{
		return $this->get($this->getUnsignedVarInt());
	}

	public function putString(string $v) : void{
		$this->putUnsignedVarInt(strlen($v));
		$this->put($v);
	}

	public function getUUID() : UUID{
		//This is actually two little-endian longs: UUID Most followed by UUID Least
		$part1 = $this->getLInt();
		$part0 = $this->getLInt();
		$part3 = $this->getLInt();
		$part2 = $this->getLInt();

		return new UUID($part0, $part1, $part2, $part3);
	}

	public function putUUID(UUID $uuid) : void{
		$this->putLInt($uuid->getPart(1));
		$this->putLInt($uuid->getPart(0));
		$this->putLInt($uuid->getPart(3));
		$this->putLInt($uuid->getPart(2));
	}

	public function getSlot() : Item{
		$id = $this->getVarInt();
		if($id === 0){
			return ItemFactory::get(0, 0, 0);
		}

		$auxValue = $this->getVarInt();
		$data = $auxValue >> 8;
		$cnt = $auxValue & 0xff;

		$nbtLen = $this->getLShort();

		/** @var CompoundTag|null $nbt */
		$nbt = null;
		if($nbtLen === 0xffff){
			$c = $this->getByte();
			if($c !== 1){
				throw new \UnexpectedValueException("Unexpected NBT count $c");
			}
			$nbt = (new NetworkLittleEndianNBTStream())->read($this->buffer, false, $this->offset, 512);
		}elseif($nbtLen !== 0){
			throw new \UnexpectedValueException("Unexpected fake NBT length $nbtLen");
		}

		//TODO
		for($i = 0, $canPlaceOn = $this->getVarInt(); $i < $canPlaceOn; ++$i){
			$this->getString();
		}

		//TODO
		for($i = 0, $canDestroy = $this->getVarInt(); $i < $canDestroy; ++$i){
			$this->getString();
		}

		if($id === ItemIds::SHIELD){
			$this->getVarLong(); //"blocking tick" (ffs mojang)
		}
		if($nbt !== null){
			if($nbt->hasTag(self::DAMAGE_TAG, IntTag::class)){
				$data = $nbt->getInt(self::DAMAGE_TAG);
				$nbt->removeTag(self::DAMAGE_TAG);
				if($nbt->count() === 0){
					$nbt = null;
					goto end;
				}
			}
			if(($conflicted = $nbt->getTag(self::DAMAGE_TAG_CONFLICT_RESOLUTION)) !== null){
				$nbt->removeTag(self::DAMAGE_TAG_CONFLICT_RESOLUTION);
				$conflicted->setName(self::DAMAGE_TAG);
				$nbt->setTag($conflicted);
			}
		}
		end:
		return ItemFactory::get($id, $data, $cnt, $nbt);
	}


	public function putSlot(Item $item) : void{
		if($item->getId() === 0){
			$this->putVarInt(0);

			return;
		}

		$this->putVarInt($item->getId());
		$auxValue = (($item->getDamage() & 0x7fff) << 8) | $item->getCount();
		$this->putVarInt($auxValue);

		$nbt = null;
		if($item->hasCompoundTag()){
			$nbt = clone $item->getNamedTag();
		}
		if($item instanceof Durable and $item->getDamage() > 0){
			if($nbt !== null){
				if(($existing = $nbt->getTag(self::DAMAGE_TAG)) !== null){
					$nbt->removeTag(self::DAMAGE_TAG);
					$existing->setName(self::DAMAGE_TAG_CONFLICT_RESOLUTION);
					$nbt->setTag($existing);
				}
			}else{
				$nbt = new CompoundTag();
			}
			$nbt->setInt(self::DAMAGE_TAG, $item->getDamage());
		}

		if($nbt !== null){
			$this->putLShort(0xffff);
			$this->putByte(1); //TODO: some kind of count field? always 1 as of 1.9.0
			$this->put((new NetworkLittleEndianNBTStream())->write($nbt));
		}else{
			$this->putLShort(0);
		}

		$this->putVarInt(0); //CanPlaceOn entry count (TODO)
		$this->putVarInt(0); //CanDestroy entry count (TODO)

		if($item->getId() === ItemIds::SHIELD){
			$this->putVarLong(0); //"blocking tick" (ffs mojang)
		}
	}

	public function getRecipeIngredient() : Item{
		$id = $this->getVarInt();
		if($id === 0){
			return ItemFactory::get(ItemIds::AIR, 0, 0);
		}
		$meta = $this->getVarInt();
		if($meta === 0x7fff){
			$meta = -1;
		}
		$count = $this->getVarInt();
		return ItemFactory::get($id, $meta, $count);
	}

	public function putRecipeIngredient(Item $item) : void{
		if($item->isNull()){
			$this->putVarInt(0);
		}else{
			$this->putVarInt($item->getId());
			$this->putVarInt($item->getDamage() & 0x7fff);
			$this->putVarInt($item->getCount());
		}
	}

	/**
	 * Decodes entity metadata from the stream.
	 *
	 * @param bool $types Whether to include metadata types along with values in the returned array
	 *
	 * @return array
	 */
	public function getEntityMetadata(bool $types = true) : array{
		$count = $this->getUnsignedVarInt();
		$data = [];
		for($i = 0; $i < $count; ++$i){
			$key = $this->getUnsignedVarInt();
			$type = $this->getUnsignedVarInt();
			$value = null;
			switch($type){
				case Entity::DATA_TYPE_BYTE:
					$value = $this->getByte();
					break;
				case Entity::DATA_TYPE_SHORT:
					$value = $this->getSignedLShort();
					break;
				case Entity::DATA_TYPE_INT:
					$value = $this->getVarInt();
					break;
				case Entity::DATA_TYPE_FLOAT:
					$value = $this->getLFloat();
					break;
				case Entity::DATA_TYPE_STRING:
					$value = $this->getString();
					break;
				case Entity::DATA_TYPE_COMPOUND_TAG:
					$value = (new NetworkLittleEndianNBTStream())->read($this->buffer, false, $this->offset, 512);
					break;
				case Entity::DATA_TYPE_POS:
					$value = new Vector3();
					$this->getSignedBlockPosition($value->x, $value->y, $value->z);
					break;
				case Entity::DATA_TYPE_LONG:
					$value = $this->getVarLong();
					break;
				case Entity::DATA_TYPE_VECTOR3F:
					$value = $this->getVector3();
					break;
				default:
					throw new \UnexpectedValueException("Invalid data type " . $type);
			}
			if($types){
				$data[$key] = [$type, $value];
			}else{
				$data[$key] = $value;
			}
		}

		return $data;
	}

	/**
	 * Writes entity metadata to the packet buffer.
	 *
	 * @param array $metadata
	 */
	public function putEntityMetadata(array $metadata) : void{
		$this->putUnsignedVarInt(count($metadata));
		foreach($metadata as $key => $d){
			$this->putUnsignedVarInt($key); //data key
			$this->putUnsignedVarInt($d[0]); //data type
			switch($d[0]){
				case Entity::DATA_TYPE_BYTE:
					$this->putByte($d[1]);
					break;
				case Entity::DATA_TYPE_SHORT:
					$this->putLShort($d[1]); //SIGNED short!
					break;
				case Entity::DATA_TYPE_INT:
					$this->putVarInt($d[1]);
					break;
				case Entity::DATA_TYPE_FLOAT:
					$this->putLFloat($d[1]);
					break;
				case Entity::DATA_TYPE_STRING:
					$this->putString($d[1]);
					break;
				case Entity::DATA_TYPE_COMPOUND_TAG:
					$this->put((new NetworkLittleEndianNBTStream())->write($d[1]));
					break;
				case Entity::DATA_TYPE_POS:
					$v = $d[1];
					if($v !== null){
						$this->putSignedBlockPosition($v->x, $v->y, $v->z);
					}else{
						$this->putSignedBlockPosition(0, 0, 0);
					}
					break;
				case Entity::DATA_TYPE_LONG:
					$this->putVarLong($d[1]);
					break;
				case Entity::DATA_TYPE_VECTOR3F:
					$this->putVector3Nullable($d[1]);
					break;
				default:
					throw new \UnexpectedValueException("Invalid data type " . $d[0]);
			}
		}
	}

	/**
	 * Reads a list of Attributes from the stream.
	 * @return Attribute[]
	 *
	 * @throws \UnexpectedValueException if reading an attribute with an unrecognized name
	 */
	public function getAttributeList() : array{
		$list = [];
		$count = $this->getUnsignedVarInt();

		for($i = 0; $i < $count; ++$i){
			$min = $this->getLFloat();
			$max = $this->getLFloat();
			$current = $this->getLFloat();
			$default = $this->getLFloat();
			$name = $this->getString();

			$attr = Attribute::getAttributeByName($name);
			if($attr !== null){
				$attr->setMinValue($min);
				$attr->setMaxValue($max);
				$attr->setValue($current);
				$attr->setDefaultValue($default);

				$list[] = $attr;
			}else{
				throw new \UnexpectedValueException("Unknown attribute type \"$name\"");
			}
		}

		return $list;
	}

	/**
	 * Writes a list of Attributes to the packet buffer using the standard format.
	 *
	 * @param Attribute ...$attributes
	 */
	public function putAttributeList(Attribute ...$attributes) : void{
		$this->putUnsignedVarInt(count($attributes));
		foreach($attributes as $attribute){
			$this->putLFloat($attribute->getMinValue());
			$this->putLFloat($attribute->getMaxValue());
			$this->putLFloat($attribute->getValue());
			$this->putLFloat($attribute->getDefaultValue());
			$this->putString($attribute->getName());
		}
	}

	/**
	 * Reads and returns an EntityUniqueID
	 * @return int
	 */
	public function getEntityUniqueId() : int{
		return $this->getVarLong();
	}

	/**
	 * Writes an EntityUniqueID
	 *
	 * @param int $eid
	 */
	public function putEntityUniqueId(int $eid) : void{
		$this->putVarLong($eid);
	}

	/**
	 * Reads and returns an EntityRuntimeID
	 * @return int
	 */
	public function getEntityRuntimeId() : int{
		return $this->getUnsignedVarLong();
	}

	/**
	 * Writes an EntityUniqueID
	 *
	 * @param int $eid
	 */
	public function putEntityRuntimeId(int $eid) : void{
		$this->putUnsignedVarLong($eid);
	}

	/**
	 * Reads an block position with unsigned Y coordinate.
	 *
	 * @param int &$x
	 * @param int &$y
	 * @param int &$z
	 */
	public function getBlockPosition(&$x, &$y, &$z) : void{
		$x = $this->getVarInt();
		$y = $this->getUnsignedVarInt();
		$z = $this->getVarInt();
	}

	/**
	 * Writes a block position with unsigned Y coordinate.
	 *
	 * @param int $x
	 * @param int $y
	 * @param int $z
	 */
	public function putBlockPosition(int $x, int $y, int $z) : void{
		$this->putVarInt($x);
		$this->putUnsignedVarInt($y);
		$this->putVarInt($z);
	}

	/**
	 * Reads a block position with a signed Y coordinate.
	 *
	 * @param int &$x
	 * @param int &$y
	 * @param int &$z
	 */
	public function getSignedBlockPosition(&$x, &$y, &$z) : void{
		$x = $this->getVarInt();
		$y = $this->getVarInt();
		$z = $this->getVarInt();
	}

	/**
	 * Writes a block position with a signed Y coordinate.
	 *
	 * @param int $x
	 * @param int $y
	 * @param int $z
	 */
	public function putSignedBlockPosition(int $x, int $y, int $z) : void{
		$this->putVarInt($x);
		$this->putVarInt($y);
		$this->putVarInt($z);
	}

	/**
	 * Reads a floating-point Vector3 object with coordinates rounded to 4 decimal places.
	 *
	 * @return Vector3
	 */
	public function getVector3() : Vector3{
		return new Vector3(
			$this->getRoundedLFloat(4),
			$this->getRoundedLFloat(4),
			$this->getRoundedLFloat(4)
		);
	}

	/**
	 * Writes a floating-point Vector3 object, or 3x zero if null is given.
	 *
	 * Note: ONLY use this where it is reasonable to allow not specifying the vector.
	 * For all other purposes, use the non-nullable version.
	 *
	 * @see NetworkBinaryStream::putVector3()
	 *
	 * @param Vector3|null $vector
	 */
	public function putVector3Nullable(?Vector3 $vector) : void{
		if($vector){
			$this->putVector3($vector);
		}else{
			$this->putLFloat(0.0);
			$this->putLFloat(0.0);
			$this->putLFloat(0.0);
		}
	}

	/**
	 * Writes a floating-point Vector3 object
	 *
	 * @param Vector3 $vector
	 */
	public function putVector3(Vector3 $vector) : void{
		$this->putLFloat($vector->x);
		$this->putLFloat($vector->y);
		$this->putLFloat($vector->z);
	}

	public function getByteRotation() : float{
		return (float) ($this->getByte() * (360 / 256));
	}

	public function putByteRotation(float $rotation) : void{
		$this->putByte((int) ($rotation / (360 / 256)));
	}

	/**
	 * Reads gamerules
	 * TODO: implement this properly
	 *
	 * @return array, members are in the structure [name => [type, value]]
	 */
	public function getGameRules() : array{
		$count = $this->getUnsignedVarInt();
		$rules = [];
		for($i = 0; $i < $count; ++$i){
			$name = $this->getString();
			$type = $this->getUnsignedVarInt();
			$value = null;
			switch($type){
				case 1:
					$value = $this->getBool();
					break;
				case 2:
					$value = $this->getUnsignedVarInt();
					break;
				case 3:
					$value = $this->getLFloat();
					break;
			}

			$rules[$name] = [$type, $value];
		}

		return $rules;
	}

	/**
	 * Writes a gamerule array, members should be in the structure [name => [type, value]]
	 * TODO: implement this properly
	 *
	 * @param array $rules
	 */
	public function putGameRules(array $rules) : void{
		$this->putUnsignedVarInt(count($rules));
		foreach($rules as $name => $rule){
			$this->putString($name);
			$this->putUnsignedVarInt($rule[0]);
			switch($rule[0]){
				case 1:
					$this->putBool($rule[1]);
					break;
				case 2:
					$this->putUnsignedVarInt($rule[1]);
					break;
				case 3:
					$this->putLFloat($rule[1]);
					break;
			}
		}
	}

	/**
	 * @return EntityLink
	 */
	protected function getEntityLink() : EntityLink{
		$link = new EntityLink();

		$link->fromEntityUniqueId = $this->getEntityUniqueId();
		$link->toEntityUniqueId = $this->getEntityUniqueId();
		$link->type = $this->getByte();
		$link->immediate = $this->getBool();

		return $link;
	}

	/**
	 * @param EntityLink $link
	 */
	protected function putEntityLink(EntityLink $link) : void{
		$this->putEntityUniqueId($link->fromEntityUniqueId);
		$this->putEntityUniqueId($link->toEntityUniqueId);
		$this->putByte($link->type);
		$this->putBool($link->immediate);
	}

	protected function getCommandOriginData() : CommandOriginData{
		$result = new CommandOriginData();

		$result->type = $this->getUnsignedVarInt();
		$result->uuid = $this->getUUID();
		$result->requestId = $this->getString();

		if($result->type === CommandOriginData::ORIGIN_DEV_CONSOLE or $result->type === CommandOriginData::ORIGIN_TEST){
			$result->varlong1 = $this->getVarLong();
		}

		return $result;
	}

	protected function putCommandOriginData(CommandOriginData $data) : void{
		$this->putUnsignedVarInt($data->type);
		$this->putUUID($data->uuid);
		$this->putString($data->requestId);

		if($data->type === CommandOriginData::ORIGIN_DEV_CONSOLE or $data->type === CommandOriginData::ORIGIN_TEST){
			$this->putVarLong($data->varlong1);
		}
	}

	protected function getStructureSettings() : StructureSettings{
		$result = new StructureSettings();

		$result->paletteName = $this->getString();

		$result->ignoreEntities = $this->getBool();
		$result->ignoreBlocks = $this->getBool();

		$this->getBlockPosition($result->structureSizeX, $result->structureSizeY, $result->structureSizeZ);
		$this->getBlockPosition($result->structureOffsetX, $result->structureOffsetY, $result->structureOffsetZ);

		$result->lastTouchedByPlayerID = $this->getEntityUniqueId();
		$result->rotation = $this->getByte();
		$result->mirror = $this->getByte();
		$result->integrityValue = $this->getFloat();
		$result->integritySeed = $this->getInt();

		return $result;
	}

	protected function putStructureSettings(StructureSettings $structureSettings) : void{
		$this->putString($structureSettings->paletteName);

		$this->putBool($structureSettings->ignoreEntities);
		$this->putBool($structureSettings->ignoreBlocks);

		$this->putBlockPosition($structureSettings->structureSizeX, $structureSettings->structureSizeY, $structureSettings->structureSizeZ);
		$this->putBlockPosition($structureSettings->structureOffsetX, $structureSettings->structureOffsetY, $structureSettings->structureOffsetZ);

		$this->putEntityUniqueId($structureSettings->lastTouchedByPlayerID);
		$this->putByte($structureSettings->rotation);
		$this->putByte($structureSettings->mirror);
		$this->putFloat($structureSettings->integrityValue);
		$this->putInt($structureSettings->integritySeed);
	}

	public function putSkin(Skin $skin) {
	    $this->putString($skin->getSkinId());
	    $this->putString($skin->getSkinResourcePatch());
	    $this->putImage($skin->getSkinData());

	    $this->putLInt(count($skin->animations)); // TODO: Animations
        foreach ($skin->animations as $animation) {
            $this->putImage($animation->image);
            $this->putLInt($animation->type);
            $this->putLFloat($animation->frames);
        }

        $this->putImage($skin->getCapeData());
        $this->putString($skin->getGeometryData());
        $this->putString($skin->getAnimationData());

        $this->putBool($skin->isPremium());
        $this->putBool($skin->isPersona());
        $this->putBool($skin->isCapeOnClassic());
        $this->putString($skin->getCapeId());

        $this->putString($skin->getFullSkinId());
    }

    public function getSkin(): Skin {
	    $skin = new Skin();
	    $skin->setSkinId($this->getString());
	    $skin->setSkinResourcePatch($this->getString());
	    $skin->setSkinData($this->getImage());

	    $count = $this->getLInt(); // TODO: Animations
        for($i = 0; $i < $count; $i++) {
            $image = $this->getImage();
            $type = $this->getLInt();
            $frames = $this->getLFloat();
            $skin->animations[] = new SkinAnimation($image, $type, $frames);
        }

        $skin->setCapeData($this->getImage());
        $skin->setGeometryData($this->getString());
        $skin->setAnimationData($this->getString());

        $skin->setPremium($this->getBool());
        $skin->setPersona($this->getBool());
        $skin->setCapeOnClassic($this->getBool());
        $skin->setCapeId($this->getString());

        $this->getString(); // TODO: Full skin id
        return $skin;
    }

    /**
     * @param SerializedImage $image
     */
    public function putImage(SerializedImage $image) {
	    $this->putLInt($image->width);
	    $this->putLInt($image->height);
	    $this->putString($image->data);
    }

    /**
     * @return SerializedImage
     */
    public function getImage(): SerializedImage {
        $width = $this->getLInt();
        $height = $this->getLInt();
        $data = $this->getString();
        return new SerializedImage($width, $height, $data);
    }

    /**
     * @param $skinId
     * @param $skinData
     * @param $skinGeometryName
     * @param $skinGeometryData
     * @param $capeData
     * @param $additionalSkinData
     */
    public function putSerializedSkin($skinId, $skinData, $skinGeometryName, $skinGeometryData, $capeData, $additionalSkinData) {
        SkinUtils::fixSkinGeometry($skinGeometryName, $additionalSkinData);
        if (!isset($additionalSkinData['PersonaSkin']) || !$additionalSkinData['PersonaSkin']) {
            $additionalSkinData = [];
        }
        if (isset($additionalSkinData['skinData'])) {
            $skinData = $additionalSkinData['skinData'];
        }
        if (isset($additionalSkinData['skinGeometryName'])) {
            $skinGeometryName = $additionalSkinData['skinGeometryName'];
        }
        if (isset($additionalSkinData['skinGeometryData'])) {
            $skinGeometryData = $additionalSkinData['skinGeometryData'];
        }
        if (empty($skinGeometryName)) {
            $skinGeometryName = "geometry.humanoid.custom";
        }
        $this->putString($skinId);
        $this->putString(isset($additionalSkinData['SkinResourcePatch']) ? $additionalSkinData['SkinResourcePatch'] : '{"geometry" : {"default" : "' . $skinGeometryName . '"}}');
        if (isset($additionalSkinData['SkinImageHeight']) && isset($additionalSkinData['SkinImageWidth'])) {
            $width = $additionalSkinData['SkinImageWidth'];
            $height = $additionalSkinData['SkinImageHeight'];
        } else {
            $width = 64;
            $height = strlen($skinData) >> 8;
            while ($height > $width) {
                $width <<= 1;
                $height >>= 1;
            }
        }
        $this->putLInt($width);
        $this->putLInt($height);
        $this->putString($skinData);
        if (isset($additionalSkinData['AnimatedImageData'])) {
            $this->putLInt(count($additionalSkinData['AnimatedImageData']));
            foreach ($additionalSkinData['AnimatedImageData'] as $animation) {
                $this->putLInt($animation['ImageWidth']);
                $this->putLInt($animation['ImageHeight']);
                $this->putString($animation['Image']);
                $this->putLInt($animation['Type']);
                $this->putLFloat($animation['Frames']);
            }
        } else {
            $this->putLInt(0);
        }

        if (empty($capeData)) {
            $this->putLInt(0);
            $this->putLInt(0);
            $this->putString('');
        } else {
            if (isset($additionalSkinData['CapeImageWidth']) && isset($additionalSkinData['CapeImageHeight'])) {
                $width = $additionalSkinData['CapeImageWidth'];
                $height = $additionalSkinData['CapeImageHeight'];
            } else {
                $width = 1;
                $height = strlen($capeData) >> 2;
                while ($height > $width) {
                    $width <<= 1;
                    $height >>= 1;
                }
            }
            $this->putLInt($width);
            $this->putLInt($height);
            $this->putString($capeData);
        }
        $this->putString($skinGeometryData); // Skin Geometry Data
        $this->putString(isset($additionalSkinData['SkinAnimationData']) ? $additionalSkinData['SkinAnimationData'] : ''); // Serialized SkinAnimation Data
        $this->putBool(isset($additionalSkinData['PremiumSkin']) ? (bool)$additionalSkinData['PremiumSkin'] : false); // Is Premium Skin
        $this->putBool(isset($additionalSkinData['PersonaSkin']) ? (bool)$additionalSkinData['PersonaSkin'] : false); // Is Persona Skin
        $this->putBool(isset($additionalSkinData['CapeOnClassicSkin']) ? (bool)$additionalSkinData['CapeOnClassicSkin'] : false); // Is Persona Cape on Classic Skin

        $this->putString(isset($additionalSkinData['CapeId']) ? $additionalSkinData['CapeId'] : '');
        $uniqId = $skinId . $skinGeometryName . "-" . microtime(true);
        $this->putString($uniqId); // Full Skin ID*/
    }

    /**
     * @param $skinId
     * @param $skinData
     * @param $skinGeometryName
     * @param $skinGeometryData
     * @param $capeData
     * @param $additionalSkinData
     */
    public function getSerializedSkin(&$skinId, &$skinData, &$skinGeometryName, &$skinGeometryData, &$capeData, &$additionalSkinData) {
        $skinId = $this->getString();
        $additionalSkinData['SkinResourcePatch'] = $this->getString();
        $geometryData = json_decode($additionalSkinData['SkinResourcePatch'], true);
        $skinGeometryName = isset($geometryData['geometry']['default']) ? $geometryData['geometry']['default'] : '';

        $additionalSkinData['SkinImageWidth'] = $this->getLInt();
        $additionalSkinData['SkinImageHeight'] = $this->getLInt();
        $skinData = $this->getString();

        $animationCount = $this->getLInt();
        $additionalSkinData['AnimatedImageData'] = [];
        for ($i = 0; $i < $animationCount; $i++) {
            $additionalSkinData['AnimatedImageData'][] = [
                'ImageWidth' => $this->getLInt(),
                'ImageHeight' => $this->getLInt(),
                'Image' => $this->getString(),
                'Type' => $this->getLInt(),
                'Frames' => $this->getLFloat(),
            ];
        }

        $additionalSkinData['CapeImageWidth'] = $this->getLInt();
        $additionalSkinData['CapeImageHeight'] = $this->getLInt();
        $capeData = $this->getString();

        $skinGeometryData = $this->getString();
        if (strpos($skinGeometryData, 'null') === 0) {
            $skinGeometryData = '';
        }
        $additionalSkinData['SkinAnimationData'] = $this->getString();
        $additionalSkinData['PremiumSkin'] = $this->getByte();
        $additionalSkinData['PersonaSkin'] = $this->getByte();
        $additionalSkinData['CapeOnClassicSkin'] = $this->getByte();

        $additionalSkinData['CapeId'] = $this->getString();
        $this->getString(); // Full Skin ID
    }
}
