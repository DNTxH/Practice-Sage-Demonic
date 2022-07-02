<?php

declare(strict_types = 1);

namespace vale\sage\demonic\commands\defaults\spawner;

use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use vale\sage\demonic\spawner\SpawnerUtils;
use pocketmine\utils\TextFormat as C;
use pocketmine\plugin\Plugin;
use pocketmine\plugin\PluginOwned;
use vale\sage\demonic\Loader;
use pocketmine\player\Player;

class SpawnerCommand extends Command implements PluginOwned {

    public function __construct() {
        parent::__construct("spawner", "Spawner management command.");
    }

    /**
     * @param CommandSender $sender
     * @param string $commandLabel
     * @param array $args
     * @return false|void
     */
    public function execute(CommandSender $sender, string $commandLabel, array $args) {
        if(!$sender->hasPermission("spawner.command")){
            $sender->sendMessage(C::RED . C::BOLD . "(!) " . C::RESET . C::RED . "No Permission to run this command");
            return false;
        }

        if(empty($args)){
            $sender->sendMessage(C::YELLOW . C::BOLD . "(!) " . C::RESET . C::YELLOW . "Usage: /spawner <spawner_name:list> <player> [amount]");
            return false;
        }

        $entities = SpawnerUtils::getEntityArrayList();

        if(isset($args[0]) && $args[0] === "list") {
            $list = implode(", ", $entities);
            $sender->sendMessage(C::RED . C::BOLD . "(!) " . C::RESET . C::RED . "List of spawners: " . $list);
            return false;
        }

        $entities = Loader::getInstance()->getSpawnerManager()->getRegisteredEntities();
        $entityName = strtolower($args[0]);

        if($entities === null){
            $sender->sendMessage(C::RED . C::BOLD . "(!) " . C::RESET . C::RED . "No Entities Registered Found");
            return false;
        }

        $entities = array_map("strtolower", $entities);

        if(!in_array(strtolower("vale\\sage\\demonic\\spawner\\entity\\" . $entityName), $entities)){
            $sender->sendMessage(C::RED . C::BOLD . "(!) " . C::RESET . C::RED . "Mob named " . $entityName . " is not registered");
            return false;
        }

        $player = $sender;

        if(isset($args[1])){
            $player = Loader::getInstance()->getServer()->getPlayerByPrefix($args[1]);
            if($player === null){
                $sender->sendMessage(C::RED . C::BOLD . "(!) " . C::RESET . C::RED . "Player not found");
                return false;
            }
        }

        $count = 1;

        if(isset($args[2]) && (int)$args[2] >= 1){
            $count = (int)$args[2];
        }

        $spawner = Loader::getInstance()->getSpawnerManager()->getSpawner($entityName, $count);
        $spawnerName = $spawner->getCustomName();

        if($player instanceof Player){
            $sender->sendMessage(C::GREEN . C::BOLD . "(!) " . C::RESET . C::GREEN . "You gave " . $player->getName() . " " . $count . "x of " . $spawnerName);
            $player->getInventory()->addItem($spawner);
        }
    }

    /**
     * @return Plugin
     */
    public function getOwningPlugin(): Plugin {
        return Loader::getInstance();
    }
}