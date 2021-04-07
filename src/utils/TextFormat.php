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

namespace pocketmine\utils;

use function is_array;
use function json_encode;
use function json_last_error_msg;
use function mb_scrub;
use function preg_last_error;
use function preg_quote;
use function preg_replace;
use function preg_split;
use function str_repeat;
use function str_replace;
use const JSON_UNESCAPED_SLASHES;
use const PREG_BACKTRACK_LIMIT_ERROR;
use const PREG_BAD_UTF8_ERROR;
use const PREG_BAD_UTF8_OFFSET_ERROR;
use const PREG_INTERNAL_ERROR;
use const PREG_JIT_STACKLIMIT_ERROR;
use const PREG_RECURSION_LIMIT_ERROR;
use const PREG_SPLIT_DELIM_CAPTURE;
use const PREG_SPLIT_NO_EMPTY;

/**
 * Class used to handle Minecraft chat format, and convert it to other formats like HTML
 */
abstract class TextFormat{
	public const ESCAPE = "\xc2\xa7"; //§
	public const EOL = "\n";

	public const BLACK = TextFormat::ESCAPE . "0";
	public const DARK_BLUE = TextFormat::ESCAPE . "1";
	public const DARK_GREEN = TextFormat::ESCAPE . "2";
	public const DARK_AQUA = TextFormat::ESCAPE . "3";
	public const DARK_RED = TextFormat::ESCAPE . "4";
	public const DARK_PURPLE = TextFormat::ESCAPE . "5";
	public const GOLD = TextFormat::ESCAPE . "6";
	public const GRAY = TextFormat::ESCAPE . "7";
	public const DARK_GRAY = TextFormat::ESCAPE . "8";
	public const BLUE = TextFormat::ESCAPE . "9";
	public const GREEN = TextFormat::ESCAPE . "a";
	public const AQUA = TextFormat::ESCAPE . "b";
	public const RED = TextFormat::ESCAPE . "c";
	public const LIGHT_PURPLE = TextFormat::ESCAPE . "d";
	public const YELLOW = TextFormat::ESCAPE . "e";
	public const WHITE = TextFormat::ESCAPE . "f";

	public const OBFUSCATED = TextFormat::ESCAPE . "k";
	public const BOLD = TextFormat::ESCAPE . "l";
	public const STRIKETHROUGH = TextFormat::ESCAPE . "m";
	public const UNDERLINE = TextFormat::ESCAPE . "n";
	public const ITALIC = TextFormat::ESCAPE . "o";
	public const RESET = TextFormat::ESCAPE . "r";

	private static function makePcreError() : \InvalidArgumentException{
		$errorCode = preg_last_error();
		$message = [
			PREG_INTERNAL_ERROR => "Internal error",
			PREG_BACKTRACK_LIMIT_ERROR => "Backtrack limit reached",
			PREG_RECURSION_LIMIT_ERROR => "Recursion limit reached",
			PREG_BAD_UTF8_ERROR => "Malformed UTF-8",
			PREG_BAD_UTF8_OFFSET_ERROR => "Bad UTF-8 offset",
			PREG_JIT_STACKLIMIT_ERROR => "PCRE JIT stack limit reached"
		][$errorCode] ?? "Unknown (code $errorCode)";
		throw new \InvalidArgumentException("PCRE error: $message");
	}

	/**
	 * @throws \InvalidArgumentException
	 */
	private static function preg_replace(string $pattern, string $replacement, string $string) : string{
		$result = preg_replace($pattern, $replacement, $string);
		if($result === null){
			throw self::makePcreError();
		}
		return $result;
	}

	/**
	 * Splits the string by Format tokens
	 *
	 * @return string[]
	 */
	public static function tokenize(string $string) : array{
		$result = preg_split("/(" . TextFormat::ESCAPE . "[0-9a-fk-or])/u", $string, -1, PREG_SPLIT_NO_EMPTY | PREG_SPLIT_DELIM_CAPTURE);
		if($result === false) throw self::makePcreError();
		return $result;
	}

	/**
	 * Cleans the string from Minecraft codes, ANSI Escape Codes and invalid UTF-8 characters
	 *
	 * @return string valid clean UTF-8
	 */
	public static function clean(string $string, bool $removeFormat = true) : string{
		$string = mb_scrub($string, 'UTF-8');
		$string = self::preg_replace("/[\x{E000}-\x{F8FF}]/u", "", $string); //remove unicode private-use-area characters (they might break the console)
		if($removeFormat){
			$string = str_replace(TextFormat::ESCAPE, "", self::preg_replace("/" . TextFormat::ESCAPE . "[0-9a-fk-or]/u", "", $string));
		}
		return str_replace("\x1b", "", self::preg_replace("/\x1b[\\(\\][[0-9;\\[\\(]+[Bm]/u", "", $string));
	}

	/**
	 * Replaces placeholders of § with the correct character. Only valid codes (as in the constants of the TextFormat class) will be converted.
	 *
	 * @param string $placeholder default "&"
	 */
	public static function colorize(string $string, string $placeholder = "&") : string{
		return self::preg_replace('/' . preg_quote($placeholder, "/") . '([0-9a-fk-or])/u', TextFormat::ESCAPE . '$1', $string);
	}

	/**
	 * Returns an JSON-formatted string with colors/markup
	 *
	 * @param string|string[] $string
	 */
	public static function toJSON($string) : string{
		if(!is_array($string)){
			$string = self::tokenize($string);
		}
		$newString = new TextFormatJsonObject();
		$pointer = $newString;
		$color = "white";
		$bold = false;
		$italic = false;
		$underlined = false;
		$strikethrough = false;
		$obfuscated = false;
		$index = 0;

		foreach($string as $token){
			if($pointer->text !== null){
				if($newString->extra === null){
					$newString->extra = [];
				}
				$newString->extra[$index] = $pointer = new TextFormatJsonObject();
				if($color !== "white"){
					$pointer->color = $color;
				}
				if($bold){
					$pointer->bold = true;
				}
				if($italic){
					$pointer->italic = true;
				}
				if($underlined){
					$pointer->underlined = true;
				}
				if($strikethrough){
					$pointer->strikethrough = true;
				}
				if($obfuscated){
					$pointer->obfuscated = true;
				}
				++$index;
			}
			switch($token){
				case TextFormat::BOLD:
					if(!$bold){
						$pointer->bold = true;
						$bold = true;
					}
					break;
				case TextFormat::OBFUSCATED:
					if(!$obfuscated){
						$pointer->obfuscated = true;
						$obfuscated = true;
					}
					break;
				case TextFormat::ITALIC:
					if(!$italic){
						$pointer->italic = true;
						$italic = true;
					}
					break;
				case TextFormat::UNDERLINE:
					if(!$underlined){
						$pointer->underlined = true;
						$underlined = true;
					}
					break;
				case TextFormat::STRIKETHROUGH:
					if(!$strikethrough){
						$pointer->strikethrough = true;
						$strikethrough = true;
					}
					break;
				case TextFormat::RESET:
					if($color !== "white"){
						$pointer->color = "white";
						$color = "white";
					}
					if($bold){
						$pointer->bold = false;
						$bold = false;
					}
					if($italic){
						$pointer->italic = false;
						$italic = false;
					}
					if($underlined){
						$pointer->underlined = false;
						$underlined = false;
					}
					if($strikethrough){
						$pointer->strikethrough = false;
						$strikethrough = false;
					}
					if($obfuscated){
						$pointer->obfuscated = false;
						$obfuscated = false;
					}
					break;

				//Colors
				case TextFormat::BLACK:
					$pointer->color = "black";
					$color = "black";
					break;
				case TextFormat::DARK_BLUE:
					$pointer->color = "dark_blue";
					$color = "dark_blue";
					break;
				case TextFormat::DARK_GREEN:
					$pointer->color = "dark_green";
					$color = "dark_green";
					break;
				case TextFormat::DARK_AQUA:
					$pointer->color = "dark_aqua";
					$color = "dark_aqua";
					break;
				case TextFormat::DARK_RED:
					$pointer->color = "dark_red";
					$color = "dark_red";
					break;
				case TextFormat::DARK_PURPLE:
					$pointer->color = "dark_purple";
					$color = "dark_purple";
					break;
				case TextFormat::GOLD:
					$pointer->color = "gold";
					$color = "gold";
					break;
				case TextFormat::GRAY:
					$pointer->color = "gray";
					$color = "gray";
					break;
				case TextFormat::DARK_GRAY:
					$pointer->color = "dark_gray";
					$color = "dark_gray";
					break;
				case TextFormat::BLUE:
					$pointer->color = "blue";
					$color = "blue";
					break;
				case TextFormat::GREEN:
					$pointer->color = "green";
					$color = "green";
					break;
				case TextFormat::AQUA:
					$pointer->color = "aqua";
					$color = "aqua";
					break;
				case TextFormat::RED:
					$pointer->color = "red";
					$color = "red";
					break;
				case TextFormat::LIGHT_PURPLE:
					$pointer->color = "light_purple";
					$color = "light_purple";
					break;
				case TextFormat::YELLOW:
					$pointer->color = "yellow";
					$color = "yellow";
					break;
				case TextFormat::WHITE:
					$pointer->color = "white";
					$color = "white";
					break;
				default:
					$pointer->text = $token;
					break;
			}
		}

		if($newString->extra !== null){
			foreach($newString->extra as $k => $d){
				if($d->text === null){
					unset($newString->extra[$k]);
				}
			}
		}

		$result = json_encode($newString, JSON_UNESCAPED_SLASHES);
		if($result === false){
			throw new \InvalidArgumentException("Failed to encode result JSON: " . json_last_error_msg());
		}
		return $result;
	}

	/**
	 * Returns an HTML-formatted string with colors/markup
	 *
	 * @param string|string[] $string
	 */
	public static function toHTML($string) : string{
		if(!is_array($string)){
			$string = self::tokenize($string);
		}
		$newString = "";
		$tokens = 0;
		foreach($string as $token){
			switch($token){
				case TextFormat::BOLD:
					$newString .= "<span style=font-weight:bold>";
					++$tokens;
					break;
				case TextFormat::OBFUSCATED:
					//$newString .= "<span style=text-decoration:line-through>";
					//++$tokens;
					break;
				case TextFormat::ITALIC:
					$newString .= "<span style=font-style:italic>";
					++$tokens;
					break;
				case TextFormat::UNDERLINE:
					$newString .= "<span style=text-decoration:underline>";
					++$tokens;
					break;
				case TextFormat::STRIKETHROUGH:
					$newString .= "<span style=text-decoration:line-through>";
					++$tokens;
					break;
				case TextFormat::RESET:
					$newString .= str_repeat("</span>", $tokens);
					$tokens = 0;
					break;

				//Colors
				case TextFormat::BLACK:
					$newString .= "<span style=color:#000>";
					++$tokens;
					break;
				case TextFormat::DARK_BLUE:
					$newString .= "<span style=color:#00A>";
					++$tokens;
					break;
				case TextFormat::DARK_GREEN:
					$newString .= "<span style=color:#0A0>";
					++$tokens;
					break;
				case TextFormat::DARK_AQUA:
					$newString .= "<span style=color:#0AA>";
					++$tokens;
					break;
				case TextFormat::DARK_RED:
					$newString .= "<span style=color:#A00>";
					++$tokens;
					break;
				case TextFormat::DARK_PURPLE:
					$newString .= "<span style=color:#A0A>";
					++$tokens;
					break;
				case TextFormat::GOLD:
					$newString .= "<span style=color:#FA0>";
					++$tokens;
					break;
				case TextFormat::GRAY:
					$newString .= "<span style=color:#AAA>";
					++$tokens;
					break;
				case TextFormat::DARK_GRAY:
					$newString .= "<span style=color:#555>";
					++$tokens;
					break;
				case TextFormat::BLUE:
					$newString .= "<span style=color:#55F>";
					++$tokens;
					break;
				case TextFormat::GREEN:
					$newString .= "<span style=color:#5F5>";
					++$tokens;
					break;
				case TextFormat::AQUA:
					$newString .= "<span style=color:#5FF>";
					++$tokens;
					break;
				case TextFormat::RED:
					$newString .= "<span style=color:#F55>";
					++$tokens;
					break;
				case TextFormat::LIGHT_PURPLE:
					$newString .= "<span style=color:#F5F>";
					++$tokens;
					break;
				case TextFormat::YELLOW:
					$newString .= "<span style=color:#FF5>";
					++$tokens;
					break;
				case TextFormat::WHITE:
					$newString .= "<span style=color:#FFF>";
					++$tokens;
					break;
				default:
					$newString .= $token;
					break;
			}
		}

		$newString .= str_repeat("</span>", $tokens);

		return $newString;
	}
}
