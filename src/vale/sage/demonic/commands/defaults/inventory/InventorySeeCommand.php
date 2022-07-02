<?php

declare(strict_types = 1);

namespace vale\sage\demonic\commands\defaults\inventory;

use muqsit\invmenu\InvMenu;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\utils\TextFormat as C;
use pocketmine\plugin\Plugin;
use pocketmine\plugin\PluginOwned;
use vale\sage\demonic\Loader;
use pocketmine\player\Player;
use pocketmine\inventory\Inventory;

class InventorySeeCommand extends Command implements PluginOwned {

    public function __construct() {
        parent::__construct("inventorysee", "See another player's inventory.", null, ["invsee"]);
    }

    /**
     * @param CommandSender $sender
     * @param string $commandLabel
     * @param array $args
     * @return bool
     */
    public function execute(CommandSender $sender, string $commandLabel, array $args) : bool {
        if(!$sender instanceof Player){
            return false;
        }

        if(!$sender->hasPermission("inventorysee.command")){
            $sender->sendMessage(C::RED . C::BOLD . "(!) " . C::RESET . C::RED . "No Permission to run this command");
            return false;
        }
        if(!isset($args[0]) || !isset($args[1])){
            $sender->sendMessage(C::YELLOW . C::BOLD . "(!) " . C::RESET . C::YELLOW . "Usage: /inventorysee <inventory:armor> <player>");
            return false;
        }
        $owner = Loader::getInstance()->getServer()->getPlayerByPrefix($args[1]);
        if($owner === null){
            $sender->sendMessage(C::RED . C::BOLD . "(!) " . C::RESET . C::RED . "Player not found");
            return false;
        }
        switch(strtolower($args[0])){
            case "inventory":
            case "inv":
                $menu = InvMenu::create(InvMenu::TYPE_DOUBLE_CHEST);
                $menu->setName(C::RESET . $owner->getName() . "'s Inventory");
                $menu->getInventory()->setContents($owner->getInventory()->getContents());
                $menu->send($sender);
                $menu->setInventoryCloseListener(function(Player $player, Inventory $inventory)use($owner): void{
                    $owner->getInventory()->setContents($inventory->getContents());
                });
                break;
            case "armor":
                $menu = InvMenu::create(InvMenu::TYPE_HOPPER);
                $menu->setName(C::RESET . $owner->getName() . "'s Armor");
                $menu->getInventory()->setContents($owner->getArmorInventory()->getContents());
                $menu->send($sender);
                $menu->setInventoryCloseListener(function(Player $player, Inventory $inventory)use($owner): void{
                    $owner->getArmorInventory()->setContents([$inventory->getItem(0), $inventory->getItem(1), $inventory->getItem(2), $inventory->getItem(3)]);
                });
                break;
            default:
                $sender->sendMessage(C::YELLOW . C::BOLD . "(!) " . C::RESET . C::YELLOW . "Usage: /inventorysee <inventory:armor> <player>");
                return false;
        }
        $menu->send($sender);
        return true;
    }

    /**
     * @return Plugin
     */
    public function getOwningPlugin(): Plugin {
        return Loader::getInstance();
    }
}