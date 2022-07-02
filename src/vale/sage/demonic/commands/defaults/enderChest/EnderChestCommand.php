<?php

declare(strict_types = 1);

namespace vale\sage\demonic\commands\defaults\enderChest;

use muqsit\invmenu\InvMenu;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\player\Player;
use pocketmine\plugin\Plugin;
use pocketmine\plugin\PluginOwned;
use vale\sage\demonic\Loader;
use pocketmine\utils\TextFormat as C;
use pocketmine\inventory\Inventory;

class EnderChestCommand extends Command implements PluginOwned {

    public function __construct() {
        parent::__construct("enderchest", "Access your ender chest.", null, ["echest"]);
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
        if(!isset($args[0])){
            $echest = InvMenu::create(InvMenu::TYPE_CHEST);
            $echest->setName(C::RESET . "Ender Chest");
            $echest->getInventory()->setContents($sender->getEnderInventory()->getContents());
            $echest->send($sender);
            $echest->setInventoryCloseListener(function(Player $player, Inventory $inventory) : void{
                $player->getEnderInventory()->setContents($inventory->getContents());
            });
        } else {
            if(!$sender->hasPermission("enderchest.command.admin")){
                $sender->sendMessage(C::RED . C::BOLD . "(!) " . C::RESET . C::RED . "No Permission to run this command");
                return false;
            }
            $owner = Loader::getInstance()->getServer()->getPlayerByPrefix($args[0]);
            if($owner === null){
                $sender->sendMessage(C::RED . C::BOLD . "(!) " . C::RESET . C::RED . "Player not found");
                return false;
            }
            $echest = InvMenu::create(InvMenu::TYPE_CHEST);
            $echest->setName(C::RESET . $owner->getName() . "'s Ender Chest");
            $echest->getInventory()->setContents($owner->getEnderInventory()->getContents());
            $echest->send($sender);
            $echest->setInventoryCloseListener(function(Player $player, Inventory $inventory)use($owner): void{
                $owner->getEnderInventory()->setContents($inventory->getContents());
            });
        }
        return true;
    }

    /**
     * @return Plugin
     */
    public function getOwningPlugin(): Plugin {
        return Loader::getInstance();
    }
}