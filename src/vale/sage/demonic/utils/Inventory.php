<?php


namespace vale\sage\demonic\utils;


use pocketmine\item\Item;
use pocketmine\item\ItemIds;
use pocketmine\player\Player;

class Inventory {

    /**
     * @param Player $player
     * @param Item $item
     */
    public static function setHelmet(Player $player, Item $item): void{
        if($player->getArmorInventory()->getHelmet()->getId() === ItemIds::AIR){
            $player->getArmorInventory()->setHelmet($item);
            return;
        }
        self::addItem($player, $item);
    }

    /**
     * @param Item $item
     */
    public static function setChestplate(Player $player, Item $item): void{
        if($player->getArmorInventory()->getChestplate()->getId() === ItemIds::AIR){
            $player->getArmorInventory()->setChestplate($item);
            return;
        }
        self::addItem($player, $item);
    }

    /**
     * @param Item $item
     */
    public static function setLeggings(Player $player, Item $item): void{
        if($player->getArmorInventory()->getLeggings()->getId() === ItemIds::AIR){
            $player->getArmorInventory()->setLeggings($item);
            return;
        }
        self::addItem($player, $item);
    }

    /**
     * @param Item $item
     */
    public static function setBoots(Player $player, Item $item): void{
        if($player->getArmorInventory()->getBoots()->getId() === ItemIds::AIR){
            $player->getArmorInventory()->setBoots($item);
            return;
        }
        self::addItem($player, $item);
    }

    /**
     * @param Player $player
     * @param Item $item
     */
    public static function addItem(Player $player, Item $item): void{
        if($player->getInventory()->canAddItem($item)){
            $player->getInventory()->addItem($item);
            return;
        }
        $player->getWorld()->dropItem($player->getPosition(), $item);
    }

}