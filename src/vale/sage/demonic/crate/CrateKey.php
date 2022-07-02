<?php

namespace vale\sage\demonic\crate;

use pocketmine\item\Item;
use pocketmine\item\ItemIds;
use pocketmine\item\ItemFactory;
use pocketmine\nbt\NBT;
use pocketmine\nbt\tag\ListTag;
use pocketmine\utils\TextFormat as C;

class CrateKey
{

    /**
     * @param int $amount
     * @return Item
     */
	public static function getSimpleKey(int $amount = 1): Item{
		$item = ItemFactory::getInstance()->get(ItemIds::TRIPWIRE_HOOK, 0, $amount);
		$item->setCustomName(C::RESET . C::GRAY . C::BOLD . "Simple" . C::WHITE . " Crate Key");
		$item->setLore([
			C::RESET . C::GRAY . "Right-Click on a " . C::GOLD . "Simple" . C::GRAY . " Crate to open.",
			" ",
			C::RESET . C::GOLD . C::BOLD . "(!) " . C::RESET . C::GOLD . "Type " . C::WHITE . "/warp crates" . C::GOLD . " to open this crate key."
		]);
		$nbt = $item->getNamedTag();
		$nbt->setString("crate", "simple");
		$nbt->setTag(Item::TAG_ENCH, new ListTag([], NBT::TAG_Compound));
		$item->setNamedTag($nbt);
		return $item;
	}

    /**
     * @param int $amount
     * @return Item
     */
	public static function getUniqueKey(int $amount = 1): Item{
		$item = ItemFactory::getInstance()->get(ItemIds::TRIPWIRE_HOOK, 0, $amount);
		$item->setCustomName(C::RESET . C::GREEN . C::BOLD . "Unique" . C::WHITE . " Crate Key");
		$item->setLore([
			C::RESET . C::GRAY . "Right-Click on a " . C::GOLD . "Unique" . C::GRAY . " Crate to open.",
			" ",
			C::RESET . C::GOLD . C::BOLD . "(!) " . C::RESET . C::GOLD . "Type " . C::WHITE . "/warp crates" . C::GOLD . " to open this crate key."
		]);
		$nbt = $item->getNamedTag();
		$nbt->setString("crate", "unique");
		$nbt->setTag(Item::TAG_ENCH, new ListTag([], NBT::TAG_Compound));
		$item->setNamedTag($nbt);
		return $item;
	}

    /**
     * @param int $amount
     * @return Item
     */
	public static function getEliteKey(int $amount = 1): Item{
		$item = ItemFactory::getInstance()->get(ItemIds::TRIPWIRE_HOOK, 0, $amount);
		$item->setCustomName(C::RESET . C::AQUA . C::BOLD . "Elite" . C::WHITE . " Crate Key");
		$item->setLore([
			C::RESET . C::GRAY . "Right-Click on a " . C::GOLD . "Elite" . C::GRAY . " Crate to open.",
			" ",
			C::RESET . C::GOLD . C::BOLD . "(!) " . C::RESET . C::GOLD . "Type " . C::WHITE . "/warp crates" . C::GOLD . " to open this crate key."
		]);
		$nbt = $item->getNamedTag();
		$nbt->setString("crate", "elite");
		$nbt->setTag(Item::TAG_ENCH, new ListTag([], NBT::TAG_Compound));
		$item->setNamedTag($nbt);
		return $item;
	}

    /**
     * @param int $amount
     * @return Item
     */
	public static function getUltimateKey(int $amount = 1): Item{
		$item = ItemFactory::getInstance()->get(ItemIds::TRIPWIRE_HOOK, 0, $amount);
		$item->setCustomName(C::RESET . C::YELLOW . C::BOLD . "Ultimate" . C::WHITE . " Crate Key");
		$item->setLore([
			C::RESET . C::GRAY . "Right-Click on a " . C::GOLD . "Ultimate" . C::GRAY . " Crate to open.",
			" ",
			C::RESET . C::GOLD . C::BOLD . "(!) " . C::RESET . C::GOLD . "Type " . C::WHITE . "/warp crates" . C::GOLD . " to open this crate key."
		]);
		$nbt = $item->getNamedTag();
		$nbt->setString("crate", "ultimate");
		$nbt->setTag(Item::TAG_ENCH, new ListTag([], NBT::TAG_Compound));
		$item->setNamedTag($nbt);
		return $item;
	}

    /**
     * @param int $amount
     * @return Item
     */
	public static function getLegendaryKey(int $amount = 1): Item{
		$item = ItemFactory::getInstance()->get(ItemIds::TRIPWIRE_HOOK, 0, $amount);
		$item->setCustomName(C::RESET . C::GOLD . C::BOLD . "Legendary" . C::WHITE . " Crate Key");
		$item->setLore([
			C::RESET . C::GRAY . "Right-Click on a " . C::GOLD . "Legendary" . C::GRAY . " Crate to open.",
			" ",
			C::RESET . C::GOLD . C::BOLD . "(!) " . C::RESET . C::GOLD . "Type " . C::WHITE . "/warp crates" . C::GOLD . " to open this crate key."
		]);
		$nbt = $item->getNamedTag();
		$nbt->setString("crate", "legendary");
		$nbt->setTag(Item::TAG_ENCH, new ListTag([], NBT::TAG_Compound));
		$item->setNamedTag($nbt);
		return $item;
	}

    /**
     * @param int $amount
     * @return Item
     */
	public static function getMasteryKey(int $amount = 1): Item{
		$item = ItemFactory::getInstance()->get(ItemIds::TRIPWIRE_HOOK, 0, $amount);
		$item->setCustomName(C::RESET . C::DARK_RED . C::BOLD . "Mastery" . C::WHITE . " Crate Key");
		$item->setLore([
			C::RESET . C::GRAY . "Right-Click on a " . C::GOLD . "Mastery" . C::GRAY . " Crate to open.",
			" ",
			C::RESET . C::GOLD . C::BOLD . "(!) " . C::RESET . C::GOLD . "Type " . C::WHITE . "/warp crates" . C::GOLD . " to open this crate key."
		]);
		$nbt = $item->getNamedTag();
		$nbt->setString("crate", "mastery");
		$nbt->setTag(Item::TAG_ENCH, new ListTag([], NBT::TAG_Compound));
		$item->setNamedTag($nbt);
		return $item;
	}
}