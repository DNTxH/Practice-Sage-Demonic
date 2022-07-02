<?php

namespace vale\sage\demonic\staff;

use pocketmine\entity\Attribute;
use pocketmine\event\Listener;
use pocketmine\item\ItemFactory;
use pocketmine\item\ItemIds;
use pocketmine\player\Player;
use pocketmine\world\Position;
use vale\sage\demonic\Loader;

class StaffManager implements Listener {

    public array $frozen = [];


    public function setInStaffMode(Player $player) : void {
        Loader::$staffMode[$player->getName()] = $player->getName();
        Loader::$savedPositons[$player->getName()] = $player->getPosition();
        Loader::$savedInventories[$player->getName()] = [
            $player->getInventory()->getContents(),
            $player->getArmorInventory()->getContents()
        ];
        $player->getInventory()->clearAll();
        $player->getArmorInventory()->clearAll();
        $player->setFlying(true);
        $player->setAllowFlight(true);
        $attr = $player->getAttributeMap()->get(Attribute::MOVEMENT_SPEED);
        $attr->setValue($attr->getValue() * 3);
        $items = [
            0 => [
                "id" => ItemIds::COMPASS,
                "name" => "§r§l§dCompass",
                "tag" => "compass",
                "lore" => ["§r§7View a List of Player\nClick them to teleport!"]
            ],
            1 => [
                "id" => ItemIds::BOOK,
                "name" => "§r§l§dInspection Book",
                "tag" => "book",
                "lore" => ["§r§7Hit any player with this to\nView their Inventorys"]
            ],
            2 => [
                "id" => ItemIds::WOODEN_AXE,
                "name" => "§r§l§dArea Logger",
                "tag" => "axe",
                "lore" => ["§r§7Select Two Positions\nTo see who interacted with them!"]
            ],
            4 => [
                "id" => ItemIds::BLUE_ICE,
                "name" => "§r§l§bFreeze Player",
                "tag" => "ice",
                "lore" => ["§r§7Hit any player with this to\nfreeze or un-freeze them!"]
            ],
            6 => [
                "id" => ItemIds::EMERALD,
                "name" => "§r§l§dTeleport To Random Player",
                "tag" => "emerald",
                "lore" => ["§r§7Use this item to teleport\nto any random player on the server."]
            ],
            7 => [
                    "id" => ItemIds::SKULL,
                    "meta" => 3,
                    "name" => "§r§l§dProfile",
                    "tag" => "skull",
                    "lore" => ["§r§7Check an Player Profile\nClick on the Player."]
            ],
            8 => [
                "id" => ItemIds::DYE,
                "meta" => 8,
                "name" => "§r§l§7UnVanish",
                "tag" => "dye",
                "lore" => ["§r§7Click to Vanish\nand to Unvanish."]
            ]
        ];
	    foreach ($items as $slot => $item) {
            $itemI = ItemFactory::getInstance()->get($item["id"], $item["meta"] ?? 0);
            $itemI->setCustomName($item["name"]);
            $itemI->setLore($item["lore"]);
            $tag = $item["tag"];
            $itemI->getNamedTag()->setString("isvalid", $tag);
            $player->getInventory()->setItem($slot, $itemI);
        }
        $player->setHealth(20);
        $player->getHungerManager()->setFood(20);
        StaffModeListener::update();
    }

    public function unsetFromStaffMode(Player $player) : void {
        unset(Loader::$staffMode[$player->getName()]);
        $inventory = Loader::$savedInventories[$player->getName()];
        $pos = Loader::$savedPositons[$player->getName()];
        unset(Loader::$savedPositons[$player->getName()]);
        unset(Loader::$savedInventories[$player->getName()]);
        $player->getInventory()->clearAll();
        $player->getArmorInventory()->clearAll();
        $player->getInventory()->setContents($inventory[0]);
        $player->getArmorInventory()->setContents($inventory[1]);
        $player->setFlying(false);
        $player->setAllowFlight(false);
        $attr = $player->getAttributeMap()->get(Attribute::MOVEMENT_SPEED);
        $attr->resetToDefault();
        $player->teleport($pos);
        $player->setHealth(20);
        $player->getHungerManager()->setFood(20);
        StaffModeListener::update();
    }

    public function isInStaffMode(Player $player) : bool {
        return in_array($player->getName(), Loader::$staffMode);
    }

}