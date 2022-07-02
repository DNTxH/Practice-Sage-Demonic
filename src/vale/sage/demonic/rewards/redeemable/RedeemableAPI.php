<?php
namespace vale\sage\demonic\rewards\redeemable;
use pocketmine\item\Item;
use pocketmine\item\ItemFactory;
use pocketmine\item\ItemIds;
use pocketmine\player\Player;
use vale\sage\demonic\crate\CrateKey;
use vale\sage\demonic\Loader;
use vale\sage\demonic\rewards\Rewards;

class RedeemableAPI
{

	/**
	 * SATCHEL TIERS
	 */
	public const SATCHEL_TIERS = [
		"Godly_Satchel" => 0,
		"Legendary_Satchel" => 1,
		"Heroic_Satchel" => 2,
		"Demonic Satchel" => 3,
	];


	/**
	 * SATCHEL TIERS
	 */
	public const Lootboxes = [
		"Thanksgiving" => 0,
		"Devils Serpent" => 1,
		"Dream_Killer" => 2,
		"Demonic_Lootbox" => 3,
	];


	/**
	 * MONEY TIERS
	 */
	public const MONEY_STASHES = [
		"Godly_Stash" => 0,
		"Legendary_Stash" => 1,
		"Heroic_Stash" => 2,
		"Demonic_Stash" => 3,
	];


	/**
	 * @param int $tier
	 * @param int $amount
	 * @return Item|null
	 */
	public static function giveSatchel(int $tier, int $amount = 1): ?Item
	{
		$item = null;
		$uniq = uniqid("UNIQUE_ID: ");
		$rand = rand(1, 1000);
		switch ($tier) {
			case 0:
				$item = ItemFactory::getInstance()->get(ItemIds::CHEST, 0, $amount);
				$item->setCustomName("§r§c§lGodly Satchel Box §r§7(Right-Click) §r§f(#$rand/0)");
				$item->setLore(array(
					"§r§7A cache of equipment packaged by",
					"§r§7the creators of this planet."
				));
				$item->getNamedTag()->setInt("satchel", 0);
				$item->getNamedTag()->setString("satchelol", "true");
				break;

			case 1:
				$item = ItemFactory::getInstance()->get(ItemIds::CHEST, 0, $amount);
				$item->setCustomName("§r§6§lLegendary Satchel Box §r§7(Right-Click) §r§f(#$rand/0)");
				$item->setLore(array(
					"§r§7A cache of equipment packaged by",
					"§r§7the creators of this planet."
				));
				$item->getNamedTag()->setInt("satchel", 1);
				$item->getNamedTag()->setString("satchelol", "true");
				break;

			case 2:
				$item = ItemFactory::getInstance()->get(ItemIds::CHEST, 0, $amount);
				$item->setCustomName("§r§d§lHeroic Satchel Box §r§7(Right-Click) §r§f(#$rand/0)");
				$item->setLore(array(
					"§r§7A cache of equipment packaged by",
					"§r§7the creators of this planet."
				));
				$item->getNamedTag()->setInt("satchel", 2);
				$item->getNamedTag()->setString("satchelol", "true");
				break;

			case 3:
				$item = ItemFactory::getInstance()->get(ItemIds::CHEST, 0, $amount);
				$item->setCustomName("§r§4§lDemonic Satchel Box §r§7(Right-Click) §r§f(#$rand/0)");
				$item->setLore(array(
					"§r§7A cache of equipment packaged by",
					"§r§7the creators of this planet."
				));
				$item->getNamedTag()->setInt("satchel", 3);
				$item->getNamedTag()->setString("satchelol", "true");
				break;
		}
		return $item;
	}

	public static function getLootBox(int $tier, int $amount = 1): ?Item
	{
		$item = null;
		$uniq = uniqid("UNIQUE_ID: ");
		$rand = rand(1, 1000);
		switch ($tier) {
			case 0:
				$item = ItemFactory::getInstance()->get(ItemIds::BEACON, 0, $amount);
				$item->setCustomName("§r§f§lLootbox: §r§e§lThanks§r§6§lgiving §r§7(Right-Click)");
				$item->getNamedTag()->setString("lootboxlol", "true");
				$item->getNamedTag()->setInt("lootbox", $tier);
				$item->setLore(array(
					"§r§7Man, im starving!",
					"§r§7Grrr..",
					"",
					"§r§7Hold on, the foods almost ready! ...",
					"",
					"§r§f§lRandom Loot (§r§75 Items§r§f§l)",
					"§r§f§l* §r§f10x §r§d§lSlot§r§f-§b§lBot §r§f§lTicket",
					"§r§f§l* §r§f1-5x §r§c§lGodly Keys",
					"",
					"",
					"",

					"§r§f§lJackpot Loot",
					"",
					"",
					"§r§f§lBonus Loot"
				));
				break;

			case 1:
				$item = ItemFactory::getInstance()->get(ItemIds::BEACON, 0, $amount);
				$item->setCustomName("§r§f§lLootbox: §r§e§lVOTE §r§7(Right-Click)");
				$item->getNamedTag()->setString("votelootbox", "true");
				$item->setLore(array(
					"§r§7Feeling, Lucky!",
					"§r§7I am too..",
					"",
					"§r§7Haha, don't be fooled good luck! ...",
					"",
					"§r§f§lRandom Loot (§r§75 Items§r§f§l)",
					"§r§f§l* §r§f10x §r§d§lSlot§r§f-§b§lBot §r§f§lTicket",
					"§r§f§l* §r§f1-5x §r§c§lGodly Keys",
					"",
					"",
					"",

					"§r§f§lJackpot Loot",
					"",
					"",
					"§r§f§lBonus Loot"
				));
				break;
		}
		return $item;
	}



	public static function getMCCrate(int $tier, int $amount = 1, Player $unlockedby = null, Item $unlockedfromitem = null): ?Item
	{
		$item = null;
		$uniq = uniqid("UNIQUE_ID: ");
		$rand = rand(1, 1000);
		$name = "";
		if($unlockedby !== null) $name = $unlockedby->getName();
		if($unlockedfromitem !== null) $name = $unlockedfromitem->getName();
		switch ($tier) {
			case 0:
				$item = ItemFactory::getInstance()->get(ItemIds::ENDER_CHEST, 0, $amount);
				$item->setCustomName("§r§l§c*§f*§9* §r§c§lSAGE CRATE §r§f§lJULY §9§l2021 §r§l§c*§f*§9*");
				$item->getNamedTag()->setString("mccratelol", "true");
				$item->getNamedTag()->setInt("mcrate", $tier);
				$item->setLore(array(
					"§r§cUnlocked by §r§c§l". $name . " at §r§9§l" . Loader::BUYCRAFT,
					"",
					"§r§f§lADMIN ITEMS",
					"§r§f§l* §r§fGrinder Mask",
					"§r§f§l* §r§fForever Alone Card",
					"",
					"§r§e§lCOSMIC ITEMS",
					"§r§e§l* §r§e3x-6 Item Rename Tags",
					"§r§e§l* §r§e1-6x Item Lore Tags",
					"§r§e§l* §r§e32x Space Fireworks",
					"",
					"§r§6§lTREASURE ITEMS",
					"§r§65-10x Sage-Slot Bot Tickets",
					"§r§61x Tier VI Satchel Box",
					"",

					"§r§2§lBONUS ITEMS"
				));
				break;

			case 1:
				$item = ItemFactory::getInstance()->get(ItemIds::BEACON, 0, $amount);
				$item->setCustomName("§r§f§lLootbox: §r§e§lVOTE §r§7(Right-Click)");
				$item->getNamedTag()->setString("votelootbox", "true");
				$item->setLore(array(
					"§r§7Feeling, Lucky!",
					"§r§7I am too..",
					"",
					"§r§7Haha, don't be fooled good luck! ...",
					"",
					"§r§f§lRandom Loot (§r§75 Items§r§f§l)",
					"§r§f§l* §r§f10x §r§d§lSlot§r§f-§b§lBot §r§f§lTicket",
					"§r§f§l* §r§f1-5x §r§c§lGodly Keys",
					"",
					"",
					"",

					"§r§f§lJackpot Loot",
					"",
					"",
					"§r§f§lBonus Loot"
				));
				break;
		}
		return $item;
	}
	public static function getSatchelRewards(int $id): array
	{
		$lol = match ($id) {
			0 => [
				Rewards::get(Rewards::ELITE_BOOK),
				Rewards::get(Rewards::ITEM_LORE_CRYSTAL),
				Rewards::get(Rewards::TEST),
				Rewards::get(Rewards::STARTER_BUNDLE),
				Rewards::createMoneyNote(null, rand(1, 1000)),
				Rewards::createXPBottle(null, rand(1, 100)),
                CrateKey::getLegendaryKey(rand(1, 2)),
                CrateKey::getMasteryKey(rand(1, 2)),

			],
			1 => [
				Rewards::get(Rewards::ELITE_BOOK),
				Rewards::get(Rewards::ITEM_LORE_CRYSTAL),
				Rewards::get(Rewards::TEST),
				Rewards::get(Rewards::STARTER_BUNDLE),
				Rewards::createMoneyNote(null, rand(1, 1000)),
				Rewards::createXPBottle(null, rand(1, 100)),
			],

			2 => [
				Rewards::get(Rewards::ELITE_BOOK),
				Rewards::get(Rewards::ITEM_LORE_CRYSTAL),
				Rewards::get(Rewards::TEST),
				Rewards::get(Rewards::STARTER_BUNDLE),
				Rewards::createMoneyNote(null, rand(1, 1000)),
				Rewards::createXPBottle(null, rand(1, 100)),
			],
			3 => [
				Rewards::get(Rewards::ELITE_BOOK),
				Rewards::get(Rewards::ITEM_LORE_CRYSTAL),
				Rewards::get(Rewards::TEST),
				Rewards::get(Rewards::STARTER_BUNDLE),
				Rewards::createMoneyNote(null, rand(1, 1000)),
				Rewards::createXPBottle(null, rand(1, 100)),
			],
		};
		return $lol;
	}


	public static function getRandomStashBox(): Item{
		$rand = rand(1,445);
		$godly  = ItemFactory::getInstance()->get(ItemIds::CHEST, 0, 1);
		$godly->setCustomName("§r§c§lGodly Random Stash Box §r§7(Right-Click) §r§f(#$rand/0)");
		$godly->setLore(array(
			"§r§7A Stash of equipment packaged by",
			"§r§7the creators of this planet.",
			"§r§7Cash prizes determiend by Rarity",
			"",
			"§r§7Tier: I"
		));
		$godly->getNamedTag()->setString("godly", 0);
		$godly->getNamedTag()->setString("stash", "true");

		$holy  = ItemFactory::getInstance()->get(ItemIds::CHEST, 0, 1);
		$holy->setCustomName("§r§e§lHoly Random Stash Box §r§7(Right-Click) §r§f(#$rand/0)");
		$holy->setLore(array(
			"§r§7A Stash of equipment packaged by",
			"§r§7the creators of this planet.",
			"§r§7Cash prizes determiend by Rarity",
			"",
			"§r§7Tier: II"
		));
		$holy->getNamedTag()->setString("holy", 0);
		$holy->getNamedTag()->setString("stash", "true");


		$elite  = ItemFactory::getInstance()->get(ItemIds::CHEST, 0, 1);
		$elite->setCustomName("§r§b§lElite Random Stash Box §r§7(Right-Click) §r§f(#$rand/0)");
		$elite->setLore(array(
			"§r§7A Stash of equipment packaged by",
			"§r§7the creators of this planet.",
			"§r§7Cash prizes determiend by Rarity",
			"",
			"§r§7Tier: II"
		));
		$elite->getNamedTag()->setString("elite", 0);
		$elite->getNamedTag()->setString("stash", "true");


		$leg  = ItemFactory::getInstance()->get(ItemIds::CHEST, 0, 1);
		$leg ->setCustomName("§r§6§lLegendary Random Stash Box §r§7(Right-Click) §r§f(#$rand/0)");
		$leg ->setLore(array(
			"§r§7A Stash of equipment packaged by",
			"§r§7the creators of this planet.",
			"§r§7Cash prizes determiend by Rarity",
			"",
			"§r§7Tier: IV"
		));
		$leg ->getNamedTag()->setString("legendary", 00);
		$leg ->getNamedTag()->setString("stash", "true");





		$hero  = ItemFactory::getInstance()->get(ItemIds::CHEST, 0, 1);
		$hero->setCustomName("§r§d§lHeroic Random Stash Box §r§7(Right-Click) §r§f(#$rand/0)");
		$hero->setLore(array(
			"§r§7A Stash of equipment packaged by",
			"§r§7the creators of this planet.",
			"§r§7Cash prizes determiend by Rarity",
			"",
			"§r§7Tier: V"
		));
		$hero->getNamedTag()->setString("hero", 0);
		$hero->getNamedTag()->setString("stash", "true");


		$demon  = ItemFactory::getInstance()->get(ItemIds::CHEST, 0, 1);
		$demon->setCustomName("§r§4§lDemonic Random Stash Box §r§7(Right-Click) §r§f(#$rand/0)");
		$demon->setLore(array(
			"§r§7A Stash of equipment packaged by",
			"§r§7the creators of this planet.",
			"§r§7Cash prizes determiend by Rarity",
			"",
			"§r§7Tier: VI"
		));
		$demon->getNamedTag()->setString("demonic", 0);
		$demon->getNamedTag()->setString("stash", "true");

		$array = [$godly, $holy, $demon, $hero, $leg, $elite];
		return $array[array_rand($array)];
	}

	public static function getLootBoxRewards(int $id): array{
		$lol =  match ($id) {
			0 => [
				Rewards::get(Rewards::ELITE_BOOK),
				Rewards::get(Rewards::ITEM_LORE_CRYSTAL),
				Rewards::get(Rewards::TEST),
				Rewards::get(Rewards::STARTER_BUNDLE),
				Rewards::createMoneyNote(null, rand(1, 1000)),
				Rewards::createXPBottle(null, rand(1, 100)),
                CrateKey::getLegendaryKey(rand(1, 2)),
                CrateKey::getMasteryKey(rand(1, 2)),

			],
			1 => [
				Rewards::get(Rewards::ELITE_BOOK),
				Rewards::get(Rewards::ITEM_LORE_CRYSTAL),
				Rewards::get(Rewards::TEST),
				Rewards::get(Rewards::STARTER_BUNDLE),
				Rewards::createMoneyNote(null, rand(1, 1000)),
				Rewards::createXPBottle(null, rand(1, 100)),
			],

			2 => [
				Rewards::get(Rewards::ELITE_BOOK),
				Rewards::get(Rewards::ITEM_LORE_CRYSTAL),
				Rewards::get(Rewards::TEST),
				Rewards::get(Rewards::STARTER_BUNDLE),
				Rewards::createMoneyNote(null, rand(1, 1000)),
				Rewards::createXPBottle(null, rand(1, 100)),
			],
			3 => [
				Rewards::get(Rewards::ELITE_BOOK),
				Rewards::get(Rewards::ITEM_LORE_CRYSTAL),
				Rewards::get(Rewards::TEST),
				Rewards::get(Rewards::STARTER_BUNDLE),
				Rewards::createMoneyNote(null, rand(1, 1000)),
				Rewards::createXPBottle(null, rand(1, 100)),
			],
		};
		return $lol;
	}

	public static function getMCRewardsById(int $id): array{
		$lol =  match ($id) {
			0 => [
				Rewards::get(Rewards::ELITE_BOOK),
				Rewards::get(Rewards::ITEM_LORE_CRYSTAL),
				Rewards::get(Rewards::TEST),
				Rewards::get(Rewards::STARTER_BUNDLE),
				Rewards::createMoneyNote(null, rand(1, 1000)),
				Rewards::createXPBottle(null, rand(1, 100)),
				CrateKey::getLegendaryKey(rand(1, 2)),
				CrateKey::getMasteryKey(rand(1, 2)),

			],
            
			1 => [
				Rewards::get(Rewards::ELITE_BOOK),
				Rewards::get(Rewards::ITEM_LORE_CRYSTAL),
				Rewards::get(Rewards::TEST),
				Rewards::get(Rewards::STARTER_BUNDLE),
				Rewards::createMoneyNote(null, rand(1, 1000)),
				Rewards::createXPBottle(null, rand(1, 100)),
			],

			2 => [
				Rewards::get(Rewards::ELITE_BOOK),
				Rewards::get(Rewards::ITEM_LORE_CRYSTAL),
				Rewards::get(Rewards::TEST),
				Rewards::get(Rewards::STARTER_BUNDLE),
				Rewards::createMoneyNote(null, rand(1, 1000)),
				Rewards::createXPBottle(null, rand(1, 100)),
			],
			3 => [
				Rewards::get(Rewards::ELITE_BOOK),
				Rewards::get(Rewards::ITEM_LORE_CRYSTAL),
				Rewards::get(Rewards::TEST),
				Rewards::get(Rewards::STARTER_BUNDLE),
				Rewards::createMoneyNote(null, rand(1, 1000)),
				Rewards::createXPBottle(null, rand(1, 100)),
			],
		};
		return $lol;
	}
	/**
	 * @param int $id
	 * @return string
	 */
	public static function getLootBoxNameById(int $id): string{
		$rand = rand(1,1000);
		return match ($id){
			0 => "§r§f§lLootbox: §r§e§lThanks§r§6§lgiving §r§7(Right-Click) §r§f(#$rand/0)",
			1 => "§r§6§lLegendary Satchel Box §r§7(Right-Click) §r§f(#$rand/0)",
			2 => "§r§d§lHeroic Satchel Box §r§7(Right-Click) §r§f(#$rand/0)",
			3 => "§r§4§lDemonic Satchel Box §r§7(Right-Click) §r§f(#$rand/0)",
		};
	}

	/**
	 * @param int $id
	 * @return string
	 */
	public static function getMCByID(int $id): string{
		$rand = rand(1,1000);
		return match ($id){
			0 => "§r§7Sage_Crate: NEW YEARS @2022",
		};
	}

	/**
	 * @param int $id
	 * @return string
	 */
	public static function getSatchelNameByID(int $id): string{
		$rand = rand(1,1000);
		return match ($id){
			0 => "§r§c§lGodly Satchel Box §r§7(Right-Click) §r§f(#$rand/0)",
			1 => "§r§6§lLegendary Satchel Box §r§7(Right-Click) §r§f(#$rand/0)",
			2 => "§r§d§lHeroic Satchel Box §r§7(Right-Click) §r§f(#$rand/0)",
			3 => "§r§4§lDemonic Satchel Box §r§7(Right-Click) §r§f(#$rand/0)",
		};
	}
}