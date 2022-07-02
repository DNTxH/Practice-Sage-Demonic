<?php

namespace vale\sage\demonic\enchants\utils;

use pocketmine\player\Player;
use pocketmine\utils\TextFormat as C;
use pocketmine\item\Item;
use pocketmine\item\ItemFactory;
use pocketmine\network\mcpe\protocol\PlaySoundPacket;

class SoulPoint
{
	public static function hasTracker(Item $item) : bool {
		if ($item->getNamedTag()->getTag('Souls') !== null) {
			return true;
		}
		return false;
	}
	
	public static function setSoul(Item $item, int $amount) : Item {
		$souls = self::getSoul($item);
		$lore = $item->getLore();
		unset($lore[array_search("Souls Collected: ". (string)$souls, $lore)]);
		$item->setLore($lore);
		array_unshift($lore , C::RESET . C::RED . "Souls Collected: " . (string)($amount));
		$item->setLore($lore);
		$item->getNamedTag()->setInt("Souls", $amount);
		
		return $item;
	}
	
	public static function getSoul(Item $item) : int {
		return $item->getNamedTag()->getTag('Souls')->getValue();
	}
}