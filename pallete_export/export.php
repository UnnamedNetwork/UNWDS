<?php

// php script used to decode blockpallet map from steadfast to pocketmine

declare(strict_types=0);

var_dump(gethostbyname("mc.bedrockplay.eu"));

return;

define("option", 2);
define("file", getcwd() . DIRECTORY_SEPARATOR . "BlockPallet370.json");
//define("export_file", getcwd() . DIRECTORY_SEPARATOR . "required_block_states_370.json");
//define("export_file", getcwd() . DIRECTORY_SEPARATOR . "block_id_map_370.json");
define("export_file", getcwd() . DIRECTORY_SEPARATOR . "advanced_block_states_370.json");

switch (option) {
    case 0:
        file_put_contents(export_file, convertToBlockStates(file));
        break;
    case 1:
        file_put_contents(export_file, convertToBlockIds(file));
        break;
    case 2:
        file_put_contents(export_file, $a = convertToStatesData(file));
        var_dump(json_decode($a, true));
        break;
}


/**
 * @param string $file
 * @return string
 */
function convertToStatesData(string $file): string {
    $json = json_decode(file_get_contents($file), true);
    $data = [];
    foreach ($json as $jsonData) {
        if(!empty($jsonData["states"])) {
            $data[$jsonData["name"]][$jsonData["data"]] = $jsonData["states"];
        }
    }
    return str_replace('"val"', '"value"', json_encode($data, JSON_PRETTY_PRINT));
}

/**
 * @param string $file
 * @return string
 */
function convertToBlockIds(string $file): string {
    $json = json_decode(file_get_contents($file), true);
    $blockIds = [];
    foreach ($json as ["name" => $name, "id" => $id]) {
        $blockIds[$name] = $id;
    }
    return json_encode($blockIds, JSON_PRETTY_PRINT);
}

/**
 * @param string $file
 * @return string
 */
function convertToBlockStates(string $file): string {
    $json = json_decode(file_get_contents($file), true);
    $states = [];
    foreach ($json as ["name" => $name, "data" => $data]) {
        $states[str_replace("minecraft:", "", $name)][] = $data;
    }
    $data = ["minecraft" => $states];
    return json_encode($data, JSON_PRETTY_PRINT);
}