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

namespace pocketmine\lang;

/**
 * This class contains constants for all the translations known to PocketMine-MP as per the used version of pmmp/Language.
 * This class is generated automatically, do NOT modify it by hand.
 */
final class KnownTranslationKeys{
	public const ABILITY_FLIGHT = "ability.flight";
	public const ABILITY_NOCLIP = "ability.noclip";
	public const ACCEPT_CMNOTES = "accept_cmnotes";
	public const ACCEPT_LICENSE = "accept_license";
	public const CHAT_TYPE_ACHIEVEMENT = "chat.type.achievement";
	public const CHAT_TYPE_ADMIN = "chat.type.admin";
	public const CHAT_TYPE_ANNOUNCEMENT = "chat.type.announcement";
	public const CHAT_TYPE_EMOTE = "chat.type.emote";
	public const CHAT_TYPE_TEXT = "chat.type.text";
	public const CMNOTES_1 = "cmnotes_1";
	public const COMMANDS_BAN_SUCCESS = "commands.ban.success";
	public const COMMANDS_BAN_USAGE = "commands.ban.usage";
	public const COMMANDS_BANIP_INVALID = "commands.banip.invalid";
	public const COMMANDS_BANIP_SUCCESS = "commands.banip.success";
	public const COMMANDS_BANIP_SUCCESS_PLAYERS = "commands.banip.success.players";
	public const COMMANDS_BANIP_USAGE = "commands.banip.usage";
	public const COMMANDS_BANLIST_IPS = "commands.banlist.ips";
	public const COMMANDS_BANLIST_PLAYERS = "commands.banlist.players";
	public const COMMANDS_BANLIST_USAGE = "commands.banlist.usage";
	public const COMMANDS_CLEAR_FAILURE_NO_ITEMS = "commands.clear.failure.no.items";
	public const COMMANDS_CLEAR_SUCCESS = "commands.clear.success";
	public const COMMANDS_CLEAR_TESTING = "commands.clear.testing";
	public const COMMANDS_DEFAULTGAMEMODE_SUCCESS = "commands.defaultgamemode.success";
	public const COMMANDS_DEFAULTGAMEMODE_USAGE = "commands.defaultgamemode.usage";
	public const COMMANDS_DEOP_MESSAGE = "commands.deop.message";
	public const COMMANDS_DEOP_SUCCESS = "commands.deop.success";
	public const COMMANDS_DEOP_USAGE = "commands.deop.usage";
	public const COMMANDS_DIFFICULTY_SUCCESS = "commands.difficulty.success";
	public const COMMANDS_DIFFICULTY_USAGE = "commands.difficulty.usage";
	public const COMMANDS_EFFECT_FAILURE_NOTACTIVE = "commands.effect.failure.notActive";
	public const COMMANDS_EFFECT_FAILURE_NOTACTIVE_ALL = "commands.effect.failure.notActive.all";
	public const COMMANDS_EFFECT_NOTFOUND = "commands.effect.notFound";
	public const COMMANDS_EFFECT_SUCCESS = "commands.effect.success";
	public const COMMANDS_EFFECT_SUCCESS_REMOVED = "commands.effect.success.removed";
	public const COMMANDS_EFFECT_SUCCESS_REMOVED_ALL = "commands.effect.success.removed.all";
	public const COMMANDS_EFFECT_USAGE = "commands.effect.usage";
	public const COMMANDS_ENCHANT_NOITEM = "commands.enchant.noItem";
	public const COMMANDS_ENCHANT_NOTFOUND = "commands.enchant.notFound";
	public const COMMANDS_ENCHANT_SUCCESS = "commands.enchant.success";
	public const COMMANDS_ENCHANT_USAGE = "commands.enchant.usage";
	public const COMMANDS_GAMEMODE_SUCCESS_OTHER = "commands.gamemode.success.other";
	public const COMMANDS_GAMEMODE_SUCCESS_SELF = "commands.gamemode.success.self";
	public const COMMANDS_GAMEMODE_USAGE = "commands.gamemode.usage";
	public const COMMANDS_GENERIC_NOTFOUND = "commands.generic.notFound";
	public const COMMANDS_GENERIC_NUM_TOOBIG = "commands.generic.num.tooBig";
	public const COMMANDS_GENERIC_NUM_TOOSMALL = "commands.generic.num.tooSmall";
	public const COMMANDS_GENERIC_PERMISSION = "commands.generic.permission";
	public const COMMANDS_GENERIC_PLAYER_NOTFOUND = "commands.generic.player.notFound";
	public const COMMANDS_GENERIC_USAGE = "commands.generic.usage";
	public const COMMANDS_GIVE_ITEM_NOTFOUND = "commands.give.item.notFound";
	public const COMMANDS_GIVE_SUCCESS = "commands.give.success";
	public const COMMANDS_GIVE_TAGERROR = "commands.give.tagError";
	public const COMMANDS_HELP_HEADER = "commands.help.header";
	public const COMMANDS_HELP_USAGE = "commands.help.usage";
	public const COMMANDS_KICK_SUCCESS = "commands.kick.success";
	public const COMMANDS_KICK_SUCCESS_REASON = "commands.kick.success.reason";
	public const COMMANDS_KICK_USAGE = "commands.kick.usage";
	public const COMMANDS_KILL_SUCCESSFUL = "commands.kill.successful";
	public const COMMANDS_ME_USAGE = "commands.me.usage";
	public const COMMANDS_MESSAGE_DISPLAY_INCOMING = "commands.message.display.incoming";
	public const COMMANDS_MESSAGE_DISPLAY_OUTGOING = "commands.message.display.outgoing";
	public const COMMANDS_MESSAGE_SAMETARGET = "commands.message.sameTarget";
	public const COMMANDS_MESSAGE_USAGE = "commands.message.usage";
	public const COMMANDS_OP_MESSAGE = "commands.op.message";
	public const COMMANDS_OP_SUCCESS = "commands.op.success";
	public const COMMANDS_OP_USAGE = "commands.op.usage";
	public const COMMANDS_PARTICLE_NOTFOUND = "commands.particle.notFound";
	public const COMMANDS_PARTICLE_SUCCESS = "commands.particle.success";
	public const COMMANDS_PLAYERS_LIST = "commands.players.list";
	public const COMMANDS_SAVE_DISABLED = "commands.save.disabled";
	public const COMMANDS_SAVE_ENABLED = "commands.save.enabled";
	public const COMMANDS_SAVE_START = "commands.save.start";
	public const COMMANDS_SAVE_SUCCESS = "commands.save.success";
	public const COMMANDS_SAY_USAGE = "commands.say.usage";
	public const COMMANDS_SEED_SUCCESS = "commands.seed.success";
	public const COMMANDS_SETWORLDSPAWN_SUCCESS = "commands.setworldspawn.success";
	public const COMMANDS_SETWORLDSPAWN_USAGE = "commands.setworldspawn.usage";
	public const COMMANDS_SPAWNPOINT_SUCCESS = "commands.spawnpoint.success";
	public const COMMANDS_SPAWNPOINT_USAGE = "commands.spawnpoint.usage";
	public const COMMANDS_STOP_START = "commands.stop.start";
	public const COMMANDS_TIME_ADDED = "commands.time.added";
	public const COMMANDS_TIME_QUERY = "commands.time.query";
	public const COMMANDS_TIME_SET = "commands.time.set";
	public const COMMANDS_TITLE_SUCCESS = "commands.title.success";
	public const COMMANDS_TITLE_USAGE = "commands.title.usage";
	public const COMMANDS_TP_SUCCESS = "commands.tp.success";
	public const COMMANDS_TP_SUCCESS_COORDINATES = "commands.tp.success.coordinates";
	public const COMMANDS_TP_USAGE = "commands.tp.usage";
	public const COMMANDS_UNBAN_SUCCESS = "commands.unban.success";
	public const COMMANDS_UNBAN_USAGE = "commands.unban.usage";
	public const COMMANDS_UNBANIP_INVALID = "commands.unbanip.invalid";
	public const COMMANDS_UNBANIP_SUCCESS = "commands.unbanip.success";
	public const COMMANDS_UNBANIP_USAGE = "commands.unbanip.usage";
	public const COMMANDS_WHITELIST_ADD_SUCCESS = "commands.whitelist.add.success";
	public const COMMANDS_WHITELIST_ADD_USAGE = "commands.whitelist.add.usage";
	public const COMMANDS_WHITELIST_DISABLED = "commands.whitelist.disabled";
	public const COMMANDS_WHITELIST_ENABLED = "commands.whitelist.enabled";
	public const COMMANDS_WHITELIST_LIST = "commands.whitelist.list";
	public const COMMANDS_WHITELIST_RELOADED = "commands.whitelist.reloaded";
	public const COMMANDS_WHITELIST_REMOVE_SUCCESS = "commands.whitelist.remove.success";
	public const COMMANDS_WHITELIST_REMOVE_USAGE = "commands.whitelist.remove.usage";
	public const COMMANDS_WHITELIST_USAGE = "commands.whitelist.usage";
	public const DEATH_ATTACK_ANVIL = "death.attack.anvil";
	public const DEATH_ATTACK_ARROW = "death.attack.arrow";
	public const DEATH_ATTACK_ARROW_ITEM = "death.attack.arrow.item";
	public const DEATH_ATTACK_CACTUS = "death.attack.cactus";
	public const DEATH_ATTACK_DROWN = "death.attack.drown";
	public const DEATH_ATTACK_EXPLOSION = "death.attack.explosion";
	public const DEATH_ATTACK_EXPLOSION_PLAYER = "death.attack.explosion.player";
	public const DEATH_ATTACK_FALL = "death.attack.fall";
	public const DEATH_ATTACK_GENERIC = "death.attack.generic";
	public const DEATH_ATTACK_INFIRE = "death.attack.inFire";
	public const DEATH_ATTACK_INWALL = "death.attack.inWall";
	public const DEATH_ATTACK_LAVA = "death.attack.lava";
	public const DEATH_ATTACK_MAGIC = "death.attack.magic";
	public const DEATH_ATTACK_MOB = "death.attack.mob";
	public const DEATH_ATTACK_ONFIRE = "death.attack.onFire";
	public const DEATH_ATTACK_OUTOFWORLD = "death.attack.outOfWorld";
	public const DEATH_ATTACK_PLAYER = "death.attack.player";
	public const DEATH_ATTACK_PLAYER_ITEM = "death.attack.player.item";
	public const DEATH_ATTACK_WITHER = "death.attack.wither";
	public const DEATH_FELL_ACCIDENT_GENERIC = "death.fell.accident.generic";
	public const DEFAULT_GAMEMODE = "default_gamemode";
	public const DEFAULT_VALUES_INFO = "default_values_info";
	public const DISCONNECTIONSCREEN_INVALIDNAME = "disconnectionScreen.invalidName";
	public const DISCONNECTIONSCREEN_INVALIDSKIN = "disconnectionScreen.invalidSkin";
	public const DISCONNECTIONSCREEN_NOREASON = "disconnectionScreen.noReason";
	public const DISCONNECTIONSCREEN_NOTAUTHENTICATED = "disconnectionScreen.notAuthenticated";
	public const DISCONNECTIONSCREEN_OUTDATEDCLIENT = "disconnectionScreen.outdatedClient";
	public const DISCONNECTIONSCREEN_OUTDATEDSERVER = "disconnectionScreen.outdatedServer";
	public const DISCONNECTIONSCREEN_RESOURCEPACK = "disconnectionScreen.resourcePack";
	public const DISCONNECTIONSCREEN_SERVERFULL = "disconnectionScreen.serverFull";
	public const ENCHANTMENT_ARROWDAMAGE = "enchantment.arrowDamage";
	public const ENCHANTMENT_ARROWFIRE = "enchantment.arrowFire";
	public const ENCHANTMENT_ARROWINFINITE = "enchantment.arrowInfinite";
	public const ENCHANTMENT_ARROWKNOCKBACK = "enchantment.arrowKnockback";
	public const ENCHANTMENT_CROSSBOWMULTISHOT = "enchantment.crossbowMultishot";
	public const ENCHANTMENT_CROSSBOWPIERCING = "enchantment.crossbowPiercing";
	public const ENCHANTMENT_CROSSBOWQUICKCHARGE = "enchantment.crossbowQuickCharge";
	public const ENCHANTMENT_CURSE_BINDING = "enchantment.curse.binding";
	public const ENCHANTMENT_CURSE_VANISHING = "enchantment.curse.vanishing";
	public const ENCHANTMENT_DAMAGE_ALL = "enchantment.damage.all";
	public const ENCHANTMENT_DAMAGE_ARTHROPODS = "enchantment.damage.arthropods";
	public const ENCHANTMENT_DAMAGE_UNDEAD = "enchantment.damage.undead";
	public const ENCHANTMENT_DIGGING = "enchantment.digging";
	public const ENCHANTMENT_DURABILITY = "enchantment.durability";
	public const ENCHANTMENT_FIRE = "enchantment.fire";
	public const ENCHANTMENT_FISHINGSPEED = "enchantment.fishingSpeed";
	public const ENCHANTMENT_FROSTWALKER = "enchantment.frostwalker";
	public const ENCHANTMENT_KNOCKBACK = "enchantment.knockback";
	public const ENCHANTMENT_LOOTBONUS = "enchantment.lootBonus";
	public const ENCHANTMENT_LOOTBONUSDIGGER = "enchantment.lootBonusDigger";
	public const ENCHANTMENT_LOOTBONUSFISHING = "enchantment.lootBonusFishing";
	public const ENCHANTMENT_MENDING = "enchantment.mending";
	public const ENCHANTMENT_OXYGEN = "enchantment.oxygen";
	public const ENCHANTMENT_PROTECT_ALL = "enchantment.protect.all";
	public const ENCHANTMENT_PROTECT_EXPLOSION = "enchantment.protect.explosion";
	public const ENCHANTMENT_PROTECT_FALL = "enchantment.protect.fall";
	public const ENCHANTMENT_PROTECT_FIRE = "enchantment.protect.fire";
	public const ENCHANTMENT_PROTECT_PROJECTILE = "enchantment.protect.projectile";
	public const ENCHANTMENT_SOUL_SPEED = "enchantment.soul_speed";
	public const ENCHANTMENT_THORNS = "enchantment.thorns";
	public const ENCHANTMENT_TRIDENTCHANNELING = "enchantment.tridentChanneling";
	public const ENCHANTMENT_TRIDENTIMPALING = "enchantment.tridentImpaling";
	public const ENCHANTMENT_TRIDENTLOYALTY = "enchantment.tridentLoyalty";
	public const ENCHANTMENT_TRIDENTRIPTIDE = "enchantment.tridentRiptide";
	public const ENCHANTMENT_UNTOUCHING = "enchantment.untouching";
	public const ENCHANTMENT_WATERWALKER = "enchantment.waterWalker";
	public const ENCHANTMENT_WATERWORKER = "enchantment.waterWorker";
	public const GAMEMODE_ADVENTURE = "gameMode.adventure";
	public const GAMEMODE_CHANGED = "gameMode.changed";
	public const GAMEMODE_CREATIVE = "gameMode.creative";
	public const GAMEMODE_SPECTATOR = "gameMode.spectator";
	public const GAMEMODE_SURVIVAL = "gameMode.survival";
	public const GAMEMODE_INFO = "gamemode_info";
	public const INVALID_PORT = "invalid_port";
	public const IP_CONFIRM = "ip_confirm";
	public const IP_GET = "ip_get";
	public const IP_WARNING = "ip_warning";
	public const ITEM_RECORD_11_DESC = "item.record_11.desc";
	public const ITEM_RECORD_13_DESC = "item.record_13.desc";
	public const ITEM_RECORD_BLOCKS_DESC = "item.record_blocks.desc";
	public const ITEM_RECORD_CAT_DESC = "item.record_cat.desc";
	public const ITEM_RECORD_CHIRP_DESC = "item.record_chirp.desc";
	public const ITEM_RECORD_FAR_DESC = "item.record_far.desc";
	public const ITEM_RECORD_MALL_DESC = "item.record_mall.desc";
	public const ITEM_RECORD_MELLOHI_DESC = "item.record_mellohi.desc";
	public const ITEM_RECORD_PIGSTEP_DESC = "item.record_pigstep.desc";
	public const ITEM_RECORD_STAL_DESC = "item.record_stal.desc";
	public const ITEM_RECORD_STRAD_DESC = "item.record_strad.desc";
	public const ITEM_RECORD_WAIT_DESC = "item.record_wait.desc";
	public const ITEM_RECORD_WARD_DESC = "item.record_ward.desc";
	public const KICK_ADMIN = "kick.admin";
	public const KICK_ADMIN_REASON = "kick.admin.reason";
	public const KICK_REASON_CHEAT = "kick.reason.cheat";
	public const LANGUAGE_NAME = "language.name";
	public const LANGUAGE_SELECTED = "language.selected";
	public const LANGUAGE_HAS_BEEN_SELECTED = "language_has_been_selected";
	public const MAX_PLAYERS = "max_players";
	public const MULTIPLAYER_PLAYER_JOINED = "multiplayer.player.joined";
	public const MULTIPLAYER_PLAYER_LEFT = "multiplayer.player.left";
	public const NAME_YOUR_SERVER = "name_your_server";
	public const OP_INFO = "op_info";
	public const OP_WARNING = "op_warning";
	public const OP_WHO = "op_who";
	public const POCKETMINE_COMMAND_ALIAS_ILLEGAL = "pocketmine.command.alias.illegal";
	public const POCKETMINE_COMMAND_ALIAS_NOTFOUND = "pocketmine.command.alias.notFound";
	public const POCKETMINE_COMMAND_ALIAS_RECURSIVE = "pocketmine.command.alias.recursive";
	public const POCKETMINE_COMMAND_BAN_IP_DESCRIPTION = "pocketmine.command.ban.ip.description";
	public const POCKETMINE_COMMAND_BAN_PLAYER_DESCRIPTION = "pocketmine.command.ban.player.description";
	public const POCKETMINE_COMMAND_BANLIST_DESCRIPTION = "pocketmine.command.banlist.description";
	public const POCKETMINE_COMMAND_CLEAR_DESCRIPTION = "pocketmine.command.clear.description";
	public const POCKETMINE_COMMAND_CLEAR_USAGE = "pocketmine.command.clear.usage";
	public const POCKETMINE_COMMAND_DEFAULTGAMEMODE_DESCRIPTION = "pocketmine.command.defaultgamemode.description";
	public const POCKETMINE_COMMAND_DEOP_DESCRIPTION = "pocketmine.command.deop.description";
	public const POCKETMINE_COMMAND_DIFFICULTY_DESCRIPTION = "pocketmine.command.difficulty.description";
	public const POCKETMINE_COMMAND_EFFECT_DESCRIPTION = "pocketmine.command.effect.description";
	public const POCKETMINE_COMMAND_ENCHANT_DESCRIPTION = "pocketmine.command.enchant.description";
	public const POCKETMINE_COMMAND_ERROR_PERMISSION = "pocketmine.command.error.permission";
	public const POCKETMINE_COMMAND_ERROR_PLAYERNOTFOUND = "pocketmine.command.error.playerNotFound";
	public const POCKETMINE_COMMAND_EXCEPTION = "pocketmine.command.exception";
	public const POCKETMINE_COMMAND_GAMEMODE_DESCRIPTION = "pocketmine.command.gamemode.description";
	public const POCKETMINE_COMMAND_GAMEMODE_FAILURE = "pocketmine.command.gamemode.failure";
	public const POCKETMINE_COMMAND_GAMEMODE_UNKNOWN = "pocketmine.command.gamemode.unknown";
	public const POCKETMINE_COMMAND_GC_CHUNKS = "pocketmine.command.gc.chunks";
	public const POCKETMINE_COMMAND_GC_CYCLES = "pocketmine.command.gc.cycles";
	public const POCKETMINE_COMMAND_GC_DESCRIPTION = "pocketmine.command.gc.description";
	public const POCKETMINE_COMMAND_GC_ENTITIES = "pocketmine.command.gc.entities";
	public const POCKETMINE_COMMAND_GC_HEADER = "pocketmine.command.gc.header";
	public const POCKETMINE_COMMAND_GC_MEMORYFREED = "pocketmine.command.gc.memoryFreed";
	public const POCKETMINE_COMMAND_GIVE_DESCRIPTION = "pocketmine.command.give.description";
	public const POCKETMINE_COMMAND_GIVE_USAGE = "pocketmine.command.give.usage";
	public const POCKETMINE_COMMAND_HELP_DESCRIPTION = "pocketmine.command.help.description";
	public const POCKETMINE_COMMAND_HELP_SPECIFICCOMMAND_DESCRIPTION = "pocketmine.command.help.specificCommand.description";
	public const POCKETMINE_COMMAND_HELP_SPECIFICCOMMAND_HEADER = "pocketmine.command.help.specificCommand.header";
	public const POCKETMINE_COMMAND_HELP_SPECIFICCOMMAND_USAGE = "pocketmine.command.help.specificCommand.usage";
	public const POCKETMINE_COMMAND_KICK_DESCRIPTION = "pocketmine.command.kick.description";
	public const POCKETMINE_COMMAND_KILL_DESCRIPTION = "pocketmine.command.kill.description";
	public const POCKETMINE_COMMAND_KILL_USAGE = "pocketmine.command.kill.usage";
	public const POCKETMINE_COMMAND_LIST_DESCRIPTION = "pocketmine.command.list.description";
	public const POCKETMINE_COMMAND_ME_DESCRIPTION = "pocketmine.command.me.description";
	public const POCKETMINE_COMMAND_NOTFOUND = "pocketmine.command.notFound";
	public const POCKETMINE_COMMAND_OP_DESCRIPTION = "pocketmine.command.op.description";
	public const POCKETMINE_COMMAND_PARTICLE_DESCRIPTION = "pocketmine.command.particle.description";
	public const POCKETMINE_COMMAND_PARTICLE_USAGE = "pocketmine.command.particle.usage";
	public const POCKETMINE_COMMAND_PLUGINS_DESCRIPTION = "pocketmine.command.plugins.description";
	public const POCKETMINE_COMMAND_PLUGINS_SUCCESS = "pocketmine.command.plugins.success";
	public const POCKETMINE_COMMAND_SAVE_DESCRIPTION = "pocketmine.command.save.description";
	public const POCKETMINE_COMMAND_SAVEOFF_DESCRIPTION = "pocketmine.command.saveoff.description";
	public const POCKETMINE_COMMAND_SAVEON_DESCRIPTION = "pocketmine.command.saveon.description";
	public const POCKETMINE_COMMAND_SAY_DESCRIPTION = "pocketmine.command.say.description";
	public const POCKETMINE_COMMAND_SEED_DESCRIPTION = "pocketmine.command.seed.description";
	public const POCKETMINE_COMMAND_SETWORLDSPAWN_DESCRIPTION = "pocketmine.command.setworldspawn.description";
	public const POCKETMINE_COMMAND_SPAWNPOINT_DESCRIPTION = "pocketmine.command.spawnpoint.description";
	public const POCKETMINE_COMMAND_STATUS_DESCRIPTION = "pocketmine.command.status.description";
	public const POCKETMINE_COMMAND_STOP_DESCRIPTION = "pocketmine.command.stop.description";
	public const POCKETMINE_COMMAND_TELL_DESCRIPTION = "pocketmine.command.tell.description";
	public const POCKETMINE_COMMAND_TIME_DESCRIPTION = "pocketmine.command.time.description";
	public const POCKETMINE_COMMAND_TIME_USAGE = "pocketmine.command.time.usage";
	public const POCKETMINE_COMMAND_TIMINGS_ALREADYENABLED = "pocketmine.command.timings.alreadyEnabled";
	public const POCKETMINE_COMMAND_TIMINGS_DESCRIPTION = "pocketmine.command.timings.description";
	public const POCKETMINE_COMMAND_TIMINGS_DISABLE = "pocketmine.command.timings.disable";
	public const POCKETMINE_COMMAND_TIMINGS_ENABLE = "pocketmine.command.timings.enable";
	public const POCKETMINE_COMMAND_TIMINGS_PASTEERROR = "pocketmine.command.timings.pasteError";
	public const POCKETMINE_COMMAND_TIMINGS_RESET = "pocketmine.command.timings.reset";
	public const POCKETMINE_COMMAND_TIMINGS_TIMINGSDISABLED = "pocketmine.command.timings.timingsDisabled";
	public const POCKETMINE_COMMAND_TIMINGS_TIMINGSREAD = "pocketmine.command.timings.timingsRead";
	public const POCKETMINE_COMMAND_TIMINGS_TIMINGSUPLOAD = "pocketmine.command.timings.timingsUpload";
	public const POCKETMINE_COMMAND_TIMINGS_TIMINGSWRITE = "pocketmine.command.timings.timingsWrite";
	public const POCKETMINE_COMMAND_TIMINGS_USAGE = "pocketmine.command.timings.usage";
	public const POCKETMINE_COMMAND_TITLE_DESCRIPTION = "pocketmine.command.title.description";
	public const POCKETMINE_COMMAND_TP_DESCRIPTION = "pocketmine.command.tp.description";
	public const POCKETMINE_COMMAND_TRANSFERSERVER_DESCRIPTION = "pocketmine.command.transferserver.description";
	public const POCKETMINE_COMMAND_TRANSFERSERVER_USAGE = "pocketmine.command.transferserver.usage";
	public const POCKETMINE_COMMAND_UNBAN_IP_DESCRIPTION = "pocketmine.command.unban.ip.description";
	public const POCKETMINE_COMMAND_UNBAN_PLAYER_DESCRIPTION = "pocketmine.command.unban.player.description";
	public const POCKETMINE_COMMAND_VERSION_DESCRIPTION = "pocketmine.command.version.description";
	public const POCKETMINE_COMMAND_VERSION_MINECRAFTVERSION = "pocketmine.command.version.minecraftVersion";
	public const POCKETMINE_COMMAND_VERSION_NOSUCHPLUGIN = "pocketmine.command.version.noSuchPlugin";
	public const POCKETMINE_COMMAND_VERSION_OPERATINGSYSTEM = "pocketmine.command.version.operatingSystem";
	public const POCKETMINE_COMMAND_VERSION_PHPJITDISABLED = "pocketmine.command.version.phpJitDisabled";
	public const POCKETMINE_COMMAND_VERSION_PHPJITENABLED = "pocketmine.command.version.phpJitEnabled";
	public const POCKETMINE_COMMAND_VERSION_PHPJITNOTSUPPORTED = "pocketmine.command.version.phpJitNotSupported";
	public const POCKETMINE_COMMAND_VERSION_PHPJITSTATUS = "pocketmine.command.version.phpJitStatus";
	public const POCKETMINE_COMMAND_VERSION_PHPVERSION = "pocketmine.command.version.phpVersion";
	public const POCKETMINE_COMMAND_VERSION_SERVERSOFTWARENAME = "pocketmine.command.version.serverSoftwareName";
	public const POCKETMINE_COMMAND_VERSION_SERVERSOFTWAREVERSION = "pocketmine.command.version.serverSoftwareVersion";
	public const POCKETMINE_COMMAND_VERSION_USAGE = "pocketmine.command.version.usage";
	public const POCKETMINE_COMMAND_WHITELIST_DESCRIPTION = "pocketmine.command.whitelist.description";
	public const POCKETMINE_COMPATIBILITYMODE_INFO = "pocketmine.compatibilitymode.info";
	public const POCKETMINE_CRASH_ARCHIVE = "pocketmine.crash.archive";
	public const POCKETMINE_CRASH_CREATE = "pocketmine.crash.create";
	public const POCKETMINE_CRASH_ERROR = "pocketmine.crash.error";
	public const POCKETMINE_CRASH_SUBMIT = "pocketmine.crash.submit";
	public const POCKETMINE_DATA_PLAYERCORRUPTED = "pocketmine.data.playerCorrupted";
	public const POCKETMINE_DATA_PLAYERNOTFOUND = "pocketmine.data.playerNotFound";
	public const POCKETMINE_DATA_PLAYEROLD = "pocketmine.data.playerOld";
	public const POCKETMINE_DATA_SAVEERROR = "pocketmine.data.saveError";
	public const POCKETMINE_DEBUG_ENABLE = "pocketmine.debug.enable";
	public const POCKETMINE_DISCONNECT_INCOMPATIBLEPROTOCOL = "pocketmine.disconnect.incompatibleProtocol";
	public const POCKETMINE_DISCONNECT_INVALIDSESSION = "pocketmine.disconnect.invalidSession";
	public const POCKETMINE_DISCONNECT_INVALIDSESSION_BADSIGNATURE = "pocketmine.disconnect.invalidSession.badSignature";
	public const POCKETMINE_DISCONNECT_INVALIDSESSION_MISSINGKEY = "pocketmine.disconnect.invalidSession.missingKey";
	public const POCKETMINE_DISCONNECT_INVALIDSESSION_TOOEARLY = "pocketmine.disconnect.invalidSession.tooEarly";
	public const POCKETMINE_DISCONNECT_INVALIDSESSION_TOOLATE = "pocketmine.disconnect.invalidSession.tooLate";
	public const POCKETMINE_LEVEL_AMBIGUOUSFORMAT = "pocketmine.level.ambiguousFormat";
	public const POCKETMINE_LEVEL_BACKGROUNDGENERATION = "pocketmine.level.backgroundGeneration";
	public const POCKETMINE_LEVEL_BADDEFAULTFORMAT = "pocketmine.level.badDefaultFormat";
	public const POCKETMINE_LEVEL_CONVERSION_FINISH = "pocketmine.level.conversion.finish";
	public const POCKETMINE_LEVEL_CONVERSION_START = "pocketmine.level.conversion.start";
	public const POCKETMINE_LEVEL_CORRUPTED = "pocketmine.level.corrupted";
	public const POCKETMINE_LEVEL_DEFAULTERROR = "pocketmine.level.defaultError";
	public const POCKETMINE_LEVEL_GENERATIONERROR = "pocketmine.level.generationError";
	public const POCKETMINE_LEVEL_INVALIDGENERATOROPTIONS = "pocketmine.level.invalidGeneratorOptions";
	public const POCKETMINE_LEVEL_LOADERROR = "pocketmine.level.loadError";
	public const POCKETMINE_LEVEL_NOTFOUND = "pocketmine.level.notFound";
	public const POCKETMINE_LEVEL_PREPARING = "pocketmine.level.preparing";
	public const POCKETMINE_LEVEL_SPAWNTERRAINGENERATIONPROGRESS = "pocketmine.level.spawnTerrainGenerationProgress";
	public const POCKETMINE_LEVEL_UNKNOWNFORMAT = "pocketmine.level.unknownFormat";
	public const POCKETMINE_LEVEL_UNKNOWNGENERATOR = "pocketmine.level.unknownGenerator";
	public const POCKETMINE_LEVEL_UNLOADING = "pocketmine.level.unloading";
	public const POCKETMINE_LEVEL_UNSUPPORTEDFORMAT = "pocketmine.level.unsupportedFormat";
	public const POCKETMINE_PLAYER_INVALIDENTITY = "pocketmine.player.invalidEntity";
	public const POCKETMINE_PLAYER_INVALIDMOVE = "pocketmine.player.invalidMove";
	public const POCKETMINE_PLAYER_LOGIN = "pocketmine.player.logIn";
	public const POCKETMINE_PLAYER_LOGOUT = "pocketmine.player.logOut";
	public const POCKETMINE_PLUGIN_ALIASERROR = "pocketmine.plugin.aliasError";
	public const POCKETMINE_PLUGIN_AMBIGUOUSMINAPI = "pocketmine.plugin.ambiguousMinAPI";
	public const POCKETMINE_PLUGIN_BADDATAFOLDER = "pocketmine.plugin.badDataFolder";
	public const POCKETMINE_PLUGIN_CIRCULARDEPENDENCY = "pocketmine.plugin.circularDependency";
	public const POCKETMINE_PLUGIN_COMMANDERROR = "pocketmine.plugin.commandError";
	public const POCKETMINE_PLUGIN_DEPRECATEDEVENT = "pocketmine.plugin.deprecatedEvent";
	public const POCKETMINE_PLUGIN_DISABLE = "pocketmine.plugin.disable";
	public const POCKETMINE_PLUGIN_DISALLOWEDBYBLACKLIST = "pocketmine.plugin.disallowedByBlacklist";
	public const POCKETMINE_PLUGIN_DISALLOWEDBYWHITELIST = "pocketmine.plugin.disallowedByWhitelist";
	public const POCKETMINE_PLUGIN_DUPLICATEERROR = "pocketmine.plugin.duplicateError";
	public const POCKETMINE_PLUGIN_DUPLICATEPERMISSIONERROR = "pocketmine.plugin.duplicatePermissionError";
	public const POCKETMINE_PLUGIN_EMPTYEXTENSIONVERSIONCONSTRAINT = "pocketmine.plugin.emptyExtensionVersionConstraint";
	public const POCKETMINE_PLUGIN_ENABLE = "pocketmine.plugin.enable";
	public const POCKETMINE_PLUGIN_EXTENSIONNOTLOADED = "pocketmine.plugin.extensionNotLoaded";
	public const POCKETMINE_PLUGIN_GENERICLOADERROR = "pocketmine.plugin.genericLoadError";
	public const POCKETMINE_PLUGIN_INCOMPATIBLEAPI = "pocketmine.plugin.incompatibleAPI";
	public const POCKETMINE_PLUGIN_INCOMPATIBLEEXTENSIONVERSION = "pocketmine.plugin.incompatibleExtensionVersion";
	public const POCKETMINE_PLUGIN_INCOMPATIBLEOS = "pocketmine.plugin.incompatibleOS";
	public const POCKETMINE_PLUGIN_INCOMPATIBLEPHPVERSION = "pocketmine.plugin.incompatiblePhpVersion";
	public const POCKETMINE_PLUGIN_INCOMPATIBLEPROTOCOL = "pocketmine.plugin.incompatibleProtocol";
	public const POCKETMINE_PLUGIN_INVALIDAPI = "pocketmine.plugin.invalidAPI";
	public const POCKETMINE_PLUGIN_INVALIDEXTENSIONVERSIONCONSTRAINT = "pocketmine.plugin.invalidExtensionVersionConstraint";
	public const POCKETMINE_PLUGIN_INVALIDMANIFEST = "pocketmine.plugin.invalidManifest";
	public const POCKETMINE_PLUGIN_LOAD = "pocketmine.plugin.load";
	public const POCKETMINE_PLUGIN_LOADERROR = "pocketmine.plugin.loadError";
	public const POCKETMINE_PLUGIN_MAINCLASSNOTFOUND = "pocketmine.plugin.mainClassNotFound";
	public const POCKETMINE_PLUGIN_MAINCLASSWRONGTYPE = "pocketmine.plugin.mainClassWrongType";
	public const POCKETMINE_PLUGIN_RESTRICTEDNAME = "pocketmine.plugin.restrictedName";
	public const POCKETMINE_PLUGIN_SPACESDISCOURAGED = "pocketmine.plugin.spacesDiscouraged";
	public const POCKETMINE_PLUGIN_UNKNOWNDEPENDENCY = "pocketmine.plugin.unknownDependency";
	public const POCKETMINE_SAVE_START = "pocketmine.save.start";
	public const POCKETMINE_SAVE_SUCCESS = "pocketmine.save.success";
	public const POCKETMINE_SERVER_AUTH_DISABLED = "pocketmine.server.auth.disabled";
	public const POCKETMINE_SERVER_AUTH_ENABLED = "pocketmine.server.auth.enabled";
	public const POCKETMINE_SERVER_AUTHPROPERTY_DISABLED = "pocketmine.server.authProperty.disabled";
	public const POCKETMINE_SERVER_AUTHPROPERTY_ENABLED = "pocketmine.server.authProperty.enabled";
	public const POCKETMINE_SERVER_AUTHWARNING = "pocketmine.server.authWarning";
	public const POCKETMINE_SERVER_DEFAULTGAMEMODE = "pocketmine.server.defaultGameMode";
	public const POCKETMINE_SERVER_DEVBUILD_ERROR1 = "pocketmine.server.devBuild.error1";
	public const POCKETMINE_SERVER_DEVBUILD_ERROR2 = "pocketmine.server.devBuild.error2";
	public const POCKETMINE_SERVER_DEVBUILD_ERROR3 = "pocketmine.server.devBuild.error3";
	public const POCKETMINE_SERVER_DEVBUILD_ERROR4 = "pocketmine.server.devBuild.error4";
	public const POCKETMINE_SERVER_DEVBUILD_ERROR5 = "pocketmine.server.devBuild.error5";
	public const POCKETMINE_SERVER_DEVBUILD_WARNING1 = "pocketmine.server.devBuild.warning1";
	public const POCKETMINE_SERVER_DEVBUILD_WARNING2 = "pocketmine.server.devBuild.warning2";
	public const POCKETMINE_SERVER_DEVBUILD_WARNING3 = "pocketmine.server.devBuild.warning3";
	public const POCKETMINE_SERVER_DONATE = "pocketmine.server.donate";
	public const POCKETMINE_SERVER_INFO = "pocketmine.server.info";
	public const POCKETMINE_SERVER_INFO_EXTENDED = "pocketmine.server.info.extended";
	public const POCKETMINE_SERVER_LICENSE = "pocketmine.server.license";
	public const POCKETMINE_SERVER_NETWORKSTART = "pocketmine.server.networkStart";
	public const POCKETMINE_SERVER_NETWORKSTARTFAILED = "pocketmine.server.networkStartFailed";
	public const POCKETMINE_SERVER_QUERY_RUNNING = "pocketmine.server.query.running";
	public const POCKETMINE_SERVER_START = "pocketmine.server.start";
	public const POCKETMINE_SERVER_STARTFINISHED = "pocketmine.server.startFinished";
	public const POCKETMINE_SERVER_TICKOVERLOAD = "pocketmine.server.tickOverload";
	public const POCKETMINE_PLUGINS = "pocketmine_plugins";
	public const POCKETMINE_WILL_START = "pocketmine_will_start";
	public const PORT_WARNING = "port_warning";
	public const POTION_ABSORPTION = "potion.absorption";
	public const POTION_BLINDNESS = "potion.blindness";
	public const POTION_CONDUITPOWER = "potion.conduitPower";
	public const POTION_CONFUSION = "potion.confusion";
	public const POTION_DAMAGEBOOST = "potion.damageBoost";
	public const POTION_DIGSLOWDOWN = "potion.digSlowDown";
	public const POTION_DIGSPEED = "potion.digSpeed";
	public const POTION_FIRERESISTANCE = "potion.fireResistance";
	public const POTION_HARM = "potion.harm";
	public const POTION_HEAL = "potion.heal";
	public const POTION_HEALTHBOOST = "potion.healthBoost";
	public const POTION_HUNGER = "potion.hunger";
	public const POTION_INVISIBILITY = "potion.invisibility";
	public const POTION_JUMP = "potion.jump";
	public const POTION_LEVITATION = "potion.levitation";
	public const POTION_MOVESLOWDOWN = "potion.moveSlowdown";
	public const POTION_MOVESPEED = "potion.moveSpeed";
	public const POTION_NIGHTVISION = "potion.nightVision";
	public const POTION_POISON = "potion.poison";
	public const POTION_REGENERATION = "potion.regeneration";
	public const POTION_RESISTANCE = "potion.resistance";
	public const POTION_SATURATION = "potion.saturation";
	public const POTION_SLOWFALLING = "potion.slowFalling";
	public const POTION_WATERBREATHING = "potion.waterBreathing";
	public const POTION_WEAKNESS = "potion.weakness";
	public const POTION_WITHER = "potion.wither";
	public const QUERY_DISABLE = "query_disable";
	public const QUERY_WARNING1 = "query_warning1";
	public const QUERY_WARNING2 = "query_warning2";
	public const SERVER_PORT = "server_port";
	public const SERVER_PROPERTIES = "server_properties";
	public const SETTING_UP_SERVER_NOW = "setting_up_server_now";
	public const SKIP_INSTALLER = "skip_installer";
	public const TILE_BED_NOSLEEP = "tile.bed.noSleep";
	public const TILE_BED_OCCUPIED = "tile.bed.occupied";
	public const TILE_BED_TOOFAR = "tile.bed.tooFar";
	public const WELCOME_TO_POCKETMINE = "welcome_to_pocketmine";
	public const WHITELIST_ENABLE = "whitelist_enable";
	public const WHITELIST_INFO = "whitelist_info";
	public const WHITELIST_WARNING = "whitelist_warning";
	public const YOU_HAVE_FINISHED = "you_have_finished";
	public const YOU_HAVE_TO_ACCEPT_THE_CMNOTES = "you_have_to_accept_the_cmnotes";
	public const YOU_HAVE_TO_ACCEPT_THE_LICENSE = "you_have_to_accept_the_license";
}
