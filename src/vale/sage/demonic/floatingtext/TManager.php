<?php

namespace vale\sage\demonic\floatingtext;

use pocketmine\player\Player;
use pocketmine\world\particle\FloatingTextParticle;
use pocketmine\world\Position;
use vale\sage\demonic\entitys\types\TextEntity;
use pocketmine\entity\Location;
use vale\sage\demonic\Loader;

class TManager
{
	//TEXT NAME => LOCATION => TEXT
	/**
	 * @var array|array[]
	 * Name Coords World Text
	 */
	public static array $texts = [
		"Join" => [20,183,82, "world", "§r§f§l*§e*§f* §6SagePvP §r§7(/spawn) §r§f§l*§e*§f* \n   §r§7This map reset on §6§l10/23/2021 §r§7@ §6§l3 PM EST §r§7(Map I) \n  §r§7Jounrey around /spawn to give yourslef clarity   \n  §r§7of the epic adventure SagePvP brings you! \n \n §r§fView a full feature list of the server \n §r§e/features \n \n §r§e§lServer: §r§fsagehcf.club \n §r§b§lDiscord: §r§fdiscord.sagepvp.org \n §2§lStore: §r§fshop.sagepvp.com \n \n §r§fIf any assistance is needed, you may use §r§6/discord§r§f. \n §r§6/sync - §r§fSync your minecraft account with our discord. \n §r§b/rank - §r§fLearn more about ranks on SagePvP. \n \n §r§d§lNITRO BOOSTING \n §r§fIf you are §r§6/sync'ed §r§fwith our discord and have nitro \n §r§fboosted, §r§fyou now have §aacess to the §6/kit nitro§r§f. \n §r§dNitro boosting §r§falso gives perks in discord. They can be \n §r§freviewed via §r§6discord.sagepvp.org §r§7§o#nitro-boosting \n \n §r§7§osagehcf.club "],
	    "Voting" => [65,183,46, "world", "§r§e§lVoting Rewards & Features \n §r§fWant a chance to win exclusive bundles, new custom enchants, \n §r§fitems, partner packages, fallen heroes, and much more? \n §r§fJoin §r§6discord.sagepvp.org §r§fand read \n §r§fthe tutorial in §r§6#information§r§f! \n \n §r§7§osagehcf.club"],
		"INFO" => [31,184,66, "world", "§r§6§lFaction Information \n §r§fLooking to give your faction a bit of an edge? \n \n Faction §r§e§lLeaders §r§fand §r§e§lCoLeaders §r§fare able to purchase Faction Upgrades! \n §r§fType §r§e§l/f upgrade §r§fto see your options! \n §r§fAnd purchase your upgrade use §6§lFaction Crystals §r§fand §r§6§lDemonic Cash! \n §r§6§lFactions §r§fcan earn §r§e§lFTOP §r§fpoints from voting and or winning them! \n \n §r§7The §r§6§lFactions §r§fwith the §e§lMOST §r§fpoint(s) will recieve Buycraft Credit take a look below! \n \n §r§6§l1. §r§f80$ \n §r§e§l2. §r§f55$ \n §r§e§l3. §r§f30$ \n \n §r§o§7Rewards can be claimed via discord!"],
		"FINFO" => [39,183,82, "world", "§r§6§lSage§ePvP §r§7(/spawn) \n §r§6§lDemonic Map #1 §r§f/spawn \n §r§fTo learn the basics of factions, use: \n §r§e§l/f help \n §f§fView a full feature list of the planet: \n §r§e§l/features"],
	];

	public static array $crates = [
		"Godly" => [130,174,121, "world","§r§c§lGodly Crate \n \n  §r§c(Contains Custom Enchantments, Kits, Keys, & Many More Items) \n §r§fLeft click for rewards. \n §r§fRight click to open. \n \n  §r§7§osagehcf.club \n §r§fPurchase now at ". Loader::BUYCRAFT],
		"Holy" => [138,174,122, "world","§r§e§lHoly Crate \n \n  §r§e(Contains Custom Enchantments, Kits, Keys, & Many More Items) \n §r§fLeft click for rewards. \n §r§fRight click to open. \n \n  §r§7§osagehcf.club \n §r§fPurchase now at ". Loader::BUYCRAFT],

	];

	/**
	 * SPAWNS TEXT
	 */
	public static function init(): void
	{
		$wM = Loader::getInstance()->getServer()->getWorldManager();
		foreach (self::$texts as $text => $data) {
			if(!$wM->isWorldLoaded($data[3])){
				$wM->loadWorld($data[3]);
			}
			$location = new Location($data[0], $data[1], $data[2], $wM->getWorldByName($data[3]), 0, 0);
			$entity = new TextEntity($location);
			$entity->updateText($data[4]);
			$entity->spawnToAll();
		}
	}

	public static function initCrates(): void
	{
		$wM = Loader::getInstance()->getServer()->getWorldManager();
		foreach (self::$crates as $text => $data) {
			$location = new Position($data[0] + 0.5, $data[1] + 2, $data[2] + 0.5, $wM->getWorldByName($data[3]));
			$ftp = new FloatingTextParticle("");
			$ftp->setTitle($data[4]);
			Loader::getInstance()->getServer()->getWorldManager()->getDefaultWorld()->addParticle($location, $ftp);
		}
	}
}