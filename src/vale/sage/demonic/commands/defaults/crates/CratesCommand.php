<?php

namespace vale\sage\demonic\commands\defaults\crates;

use pocketmine\command\CommandSender;
use pocketmine\command\Command;
use pocketmine\lang\Translatable;
use pocketmine\plugin\PluginOwned;
use pocketmine\plugin\Plugin;
use vale\sage\demonic\Loader;
use pocketmine\utils\TextFormat as C;

class CratesCommand extends Command implements PluginOwned
{

    public function __construct() {
        parent::__construct("crates", "Crate management command.", null, ["crate"]);
    }

    /**
     * @param CommandSender $sender
     * @param string $commandLabel
     * @param array $args
     * @return bool
     */
    public function execute(CommandSender $sender, string $commandLabel, array $args) : bool {
        if(count($args) < 2){
            $sender->sendMessage(C::YELLOW . C::BOLD . "(!) " . C::RESET . C::YELLOW . "Usage: /crate <type> <player:all> [amount]");
            return false;
        }
        if(($crate = Loader::getInstance()->getCrateManager()->getCrateByName($args[0])) === null){
            $sender->sendMessage(C::RED . C::BOLD . "(!) " . C::RESET . C::RED . "Crate type not found");
            return false;
        }
        $amount = 1;
        if(isset($args[2])){
            $amount = (int) $args[2];
            if($amount < 0){
                $amount = 1;
            }
        }
        if(in_array(strtolower($args[1]), ["all", "@a", "everyone"])){
            foreach(Loader::getInstance()->getServer()->getOnlinePlayers() as $player){
                foreach($player->getInventory()->addItem($crate->getKey()->setCount($amount)) as $item) $player->getWorld()->dropItem($player->getPosition(), $item);
            }
            Loader::getInstance()->getServer()->broadcastMessage(C::GREEN . C::BOLD . "(!) " . C::RESET . C::GREEN . "CRATE KEY ALL: " . $amount . "x " . $crate->getName());
        }else{
            $player = Loader::getInstance()->getServer()->getPlayerByPrefix($args[1]);
            if($player === null){
                $sender->sendMessage(C::RED . C::BOLD . "(!) " . C::RESET . C::RED . "Player not found");
                return false;
            }
            foreach($player->getInventory()->addItem($crate->getKey()->setCount($amount)) as $item) $player->getWorld()->dropItem($player->getPosition(), $item);
            $player->sendMessage(C::GREEN . C::BOLD . "(!) " . C::RESET . C::GREEN . "You recieved a crate key " . $amount . "x of " . $crate->getName());
            $sender->sendMessage(C::GREEN . C::BOLD . "(!) " . C::RESET . C::GREEN . "Given " . $player->getName() . " a crate key " . $amount . "x of " . $crate->getName());
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