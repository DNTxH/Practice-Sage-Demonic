<?php

namespace vale\sage\demonic\commands\defaults\collectionChest;

use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\player\Player;
use pocketmine\utils\TextFormat as C;
use pocketmine\plugin\Plugin;
use pocketmine\plugin\PluginOwned;
use vale\sage\demonic\Loader;

class CollectionChestCommand extends Command implements PluginOwned {

    public function __construct() {
        parent::__construct("cchest", "Collection chets management command.");
    }

    /**
     * @param CommandSender $sender
     * @param string $commandLabel
     * @param array $args
     * @return void
     */
    public function execute(CommandSender $sender, string $commandLabel, array $args) : void {
        if(!$sender->hasPermission("collectionchest.command")){
            $sender->sendMessage(C::RED . C::BOLD . "(!) " . C::RESET . C::RED . "No Permission to run this command");
            return;
        }

        if(!isset($args[0])){
            $sender->sendMessage(C::YELLOW . C::BOLD . "(!) " . C::RESET . C::YELLOW . "Usage: /cchest <player> [amount]");
            return;
        }

        $player = [];

        if(strtolower($args[0]) === "all"){
            $player = Loader::getInstance()->getServer()->getOnlinePlayers();
        }else{
            $player = Loader::getInstance()->getServer()->getPlayerByPrefix($args[0]);

            if($player === null){
                $sender->sendMessage(C::RED . C::BOLD . "(!) " . C::RESET . C::RED . "Player not found");
                return;
            }
        }

        $amount = 1;

        if(isset($args[1])){
            $amount = (int)$args[1];
            if($amount <= 0){
                $amount = 1;
            }
        }

        if($player instanceof Player){
            $player->getInventory()->addItem(Loader::getInstance()->getCollectionManager()->getItem()->setCount($amount));
            $player->sendMessage(C::GREEN . C::BOLD . "(!) " . C::RESET . C::GREEN . "You recieved a collection chest " . $amount . "x");
            $sender->sendMessage(C::GREEN . C::BOLD . "(!) " . C::RESET . C::GREEN . "Given " . $player->getName() . " a collection chest " . $amount . "x");
        }else{
            foreach($player as $user){
                $user->getInventory()->addItem(Loader::getInstance()->getCollectionManager()->getItem()->setCount($amount));
            }
            Loader::getInstance()->getServer()->broadcastMessage(C::GREEN . C::BOLD . "(!) " . C::RESET . C::GREEN . "COLLECTION CHEST ALL: " . $amount . "x");
        }
    }

    /**
     * @return Plugin
     */
    public function getOwningPlugin(): Plugin {
        return Loader::getInstance();
    }
}