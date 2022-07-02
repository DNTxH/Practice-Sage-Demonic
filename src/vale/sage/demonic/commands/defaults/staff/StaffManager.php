<?php

namespace vale\sage\demonic\commands\defaults\staff;

use vale\sage\demonic\Loader;
use pocketmine\entity\Attribute;
use pocketmine\inventory\ArmorInventory;
use pocketmine\inventory\Inventory;
use pocketmine\item\ItemFactory;
use pocketmine\item\ItemIds;
use pocketmine\player\Player;
use pocketmine\world\Position;

class StaffManager {

    private array $staffMode = [];

    /**
     * @var array<string, array<array, array>
     */
    private array $savedInventories = [];

    /**
     * @var array<string, Position>
     */
    private array $savedPositons = [];

    public array $frozen = [];

    public function __construct() {
        $commandMap = Loader::getInstance()->getServer()->getCommandMap();
        $unregister = [
            "ban",
            "kick",
            "ban-ip"
        ];
        foreach ($unregister as $cmd) {
            if (!$commandMap->getCommand($cmd)) continue;
            $commandMap->unregister($commandMap->getCommand($cmd));
        }
        Loader::getInstance()->getServer()->getPluginManager()->registerEvents(new StaffModeListener(), Loader::getInstance());
    }

    public function setInStaffMode(Player $player) : void {
        $this->staffMode[] = $player->getName();
        $this->savedPositons[$player->getName()] = $player->getPosition();
        $this->savedInventories[$player->getName()] = [
            $player->getInventory()->getContents(),
            $player->getArmorInventory()->getContents()
        ];
        $player->getInventory()->clearAll();
        $player->getArmorInventory()->clearAll();
        $player->setInvisible(true);
        $player->setFlying(true);
        $player->setAllowFlight(true);
        $attr = $player->getAttributeMap()->get(Attribute::MOVEMENT_SPEED);
        $attr->setValue($attr->getValue() * 3);
        $items = [
            0 => [
                "id" => ItemIds::BLUE_ICE,
                "name" => "§r§l§bFreeze Player",
                "lore" => ["§r§7Hit any player with this to\nfreeze or un-freeze them!"]
            ],
            1 => [
                "id" => ItemIds::STICK,
                "name" => "§r§l§bTeleport To Random Player",
                "lore" => ["§r§7Use this item to teleport\nto any random player on the server."]
            ]
        ];
        foreach ($items as $slot => $item) {
            $itemI = ItemFactory::getInstance()->get($item["id"]);
            $itemI->setCustomName($item["name"]);
            $itemI->setLore($item["lore"]);
            $player->getInventory()->setItem($slot, $itemI);
        }
        $player->setHealth(20);
        $player->getHungerManager()->setFood(20);
    }

    public function unsetFromStaffMode(Player $player) : void {
        unset($this->staffMode[$player->getName()]);
        $new = [];
        foreach($this->staffMode as $e) {
            if ($e == $player->getName()) continue;
            $new[] = $e;
        }
        $this->staffMode[] = $e;
        $inventory = $this->savedInventories[$player->getName()];
        $pos = $this->savedPositons[$player->getName()];
        unset($this->savedPositons[$player->getName()]);
        unset($this->savedInventories[$player->getName()]);
        $player->getInventory()->clearAll();
        $player->getArmorInventory()->clearAll();
        $player->getInventory()->setContents($inventory[0]);
        $player->getArmorInventory()->setContents($inventory[1]);
        $player->setFlying(false);
        $player->setAllowFlight(false);
        $player->setInvisible(false);
        $attr = $player->getAttributeMap()->get(Attribute::MOVEMENT_SPEED);
        $attr->resetToDefault();
        $player->teleport($pos);
        $player->setHealth(20);
        $player->getHungerManager()->setFood(20);
    }

    public function isInStaffMode(Player $player) : bool {
        return in_array($player->getName(), $this->staffMode);
    }

}