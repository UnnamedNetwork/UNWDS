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

namespace pocketmine\network\mcpe\protocol\types;

use pocketmine\block\BlockIds;
use pocketmine\nbt\BigEndianNBTStream;
use pocketmine\nbt\NetworkLittleEndianNBTStream;
use pocketmine\nbt\tag\CompoundTag;
use pocketmine\nbt\tag\ListTag;
use pocketmine\network\mcpe\protocol\ProtocolInfo;
use pocketmine\network\mcpe\protocol\StartGamePacket;
use function file_get_contents;
use function getmypid;
use function json_decode;
use function mt_rand;
use function mt_srand;
use function shuffle;

/**
 * @internal
 */
final class RuntimeBlockMapping{

    /** @var RuntimeBlockMapping[] $pallet */
    private static $pallet = [];

    /** @var int[] */
    private $legacyToRuntimeMap = [];
    /** @var int[] */
    private $runtimeToLegacyMap = [];
    /** @var string|null */
    private $bedrockKnownStates = null;

    public function __construct(int $protocol){
        switch ($protocol) {
            case ProtocolInfo::PROTOCOL_1_12:
                $this->loadFromJson();
                break;
            case ProtocolInfo::PROTOCOL_1_13:
                $this->loadFromNBT();
                break;
        }

    }

    private function loadFromNBT() {
        $compressedTable = new BigEndianNBTStream();
        /** @var ListTag $tag */
        $tag = $compressedTable->read(file_get_contents(\pocketmine\RESOURCE_PATH . "vanilla/runtime_block_states_1_13.dat"))["Palette"];

        /** @var CompoundTag $value*/
        foreach($tag->getAllValues() as $i => $value) {
            $id = $value->getShort("id");
            $meta = $value->getShort("meta");
            self::registerMapping($i, $id, $meta);

            $value->offsetUnset("meta");
        }

        $stream = new NetworkLittleEndianNBTStream();
        $stream->write(["Palette" => $tag]);
        $this->bedrockKnownStates = $stream->buffer;
    }

    private function loadFromJson() {
        $legacyIdMap = json_decode(file_get_contents(\pocketmine\RESOURCE_PATH . "vanilla/block_id_map.json"), true);

        $compressedTable = json_decode(file_get_contents(\pocketmine\RESOURCE_PATH . "vanilla/required_block_states.json"), true);
        $decompressed = [];

        foreach($compressedTable as $prefix => $entries){
            foreach($entries as $shortStringId => $states){
                foreach($states as $state){
                    $name = "$prefix:$shortStringId";
                    $decompressed[] = [
                        "name" => $name,
                        "data" => $state,
                        "legacy_id" => $legacyIdMap[$name]
                    ];
                }
            }
        }

        $this->bedrockKnownStates = $this->randomizeTable($decompressed);

        foreach($this->bedrockKnownStates as $k => $obj){
            if($obj["data"] > 15){
                //TODO: in 1.12 they started using data values bigger than 4 bits which we can't handle right now
                continue;
            }
            //this has to use the json offset to make sure the mapping is consistent with what we send over network, even though we aren't using all the entries
            $this->registerMapping($k, $obj["legacy_id"], $obj["data"]);
        }

        $this->bedrockKnownStates = StartGamePacket::serializeBlockTable($this->bedrockKnownStates); // TODO: Better multiversion api
    }

    public static function init(): void {
        self::$pallet[ProtocolInfo::PROTOCOL_1_12] = new RuntimeBlockMapping(ProtocolInfo::PROTOCOL_1_12);
        self::$pallet[ProtocolInfo::PROTOCOL_1_13] = new RuntimeBlockMapping(ProtocolInfo::PROTOCOL_1_13);
        self::$pallet[ProtocolInfo::PROTOCOL_1_14] = new RuntimeBlockMapping(ProtocolInfo::PROTOCOL_1_13); // seems equals to 1.13
    }

    private static function lazyInit(): void{
        if(empty(self::$pallet)){
            self::init();
        }
    }

    /**
     * Randomizes the order of the runtimeID table to prevent plugins relying on them.
     * Plugins shouldn't use this stuff anyway, but plugin devs have an irritating habit of ignoring what they
     * aren't supposed to do, so we have to deliberately break it to make them stop.
     *
     * @param array $table
     *
     * @return array
     */
    private static function randomizeTable(array $table) : array{
        $postSeed = mt_rand(); //save a seed to set afterwards, to avoid poor quality randoms
        mt_srand(getmypid() ?: 0); //Use a seed which is the same on all threads. This isn't a secure seed, but we don't care.
        shuffle($table);
        mt_srand($postSeed); //restore a good quality seed that isn't dependent on PID
        return $table;
    }

    /**
     * @param int $id
     * @param int $meta
     * @param int $protocol
     *
     * @return int
     */
    public static function toStaticRuntimeId(int $id, int $meta = 0, int $protocol = ProtocolInfo::CURRENT_PROTOCOL) : int{
        self::lazyInit();
        $pallet = self::$pallet[$protocol];

        /*
         * try id+meta first
         * if not found, try id+0 (strip meta)
         * if still not found, return update! block
         */
        return $pallet->legacyToRuntimeMap[($id << 4) | $meta] ?? $pallet->legacyToRuntimeMap[$id << 4] ?? $pallet->legacyToRuntimeMap[BlockIds::INFO_UPDATE << 4];
    }

    /**
     * @param int $runtimeId
     * @param int $protocol
     *
     * @return int[] [id, meta]
     */
    public static function fromStaticRuntimeId(int $runtimeId, int $protocol = ProtocolInfo::CURRENT_PROTOCOL) : array{
        self::lazyInit();
        $v = self::$pallet[$protocol]->runtimeToLegacyMap[$runtimeId];
        return [$v >> 4, $v & 0xf];
    }

    /**
     * @param int $staticRuntimeId
     * @param int $legacyId
     * @param int $legacyMeta
     */
    private function registerMapping(int $staticRuntimeId, int $legacyId, int $legacyMeta): void {
        $this->legacyToRuntimeMap[($legacyId << 4) | $legacyMeta] = $staticRuntimeId;
        $this->runtimeToLegacyMap[$staticRuntimeId] = ($legacyId << 4) | $legacyMeta;
    }

    /**
     * @param int $protocol
     * @return string
     */
    public static function getBedrockKnownStates(int $protocol = ProtocolInfo::CURRENT_PROTOCOL): string {
        self::lazyInit();
        return self::$pallet[$protocol]->bedrockKnownStates;
    }
}