<?php

declare(strict_types=1);

namespace pocketmine\utils;

use pocketmine\network\mcpe\protocol\LoginPacket;

/**
 * Class SkinUtils
 * @package pocketmine\utils
 */
class SkinUtils {


    /**
     * @param $skinGeometryName
     * @param $additionalSkinData
     */
    public static function fixSkinGeometry(&$skinGeometryName, $additionalSkinData) {
        if (empty($skinGeometryName) && !empty($additionalSkinData['SkinResourcePatch'])) {
            if (($jsonSkinData = @json_decode($additionalSkinData['SkinResourcePatch'], true)) && isset($jsonSkinData['geometry']['default'])) {
                $skinGeometryName = $jsonSkinData['geometry']['default'];
            }
        }
    }
}