<?php

declare(strict_types = 1);

namespace vale\sage\demonic\commands\defaults\homes;

use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\utils\TextFormat as C;
use pocketmine\player\Player;
use pocketmine\plugin\Plugin;
use pocketmine\plugin\PluginOwned;
use vale\sage\demonic\Loader;

class DeleteHomeCommand extends Command implements PluginOwned {

    public function __construct() {
        parent::__construct("deletehome", "Delete a home.");
    }

    /**
     * @param CommandSender $sender
     * @param string $commandLabel
     * @param array $args
     * @return false|void
     */
    public function execute(CommandSender $sender, string $commandLabel, array $args) {
        if(!$sender instanceof Player){
            return false;
        }

        if(!isset($args[0])){
            $sender->sendMessage(C::YELLOW . C::BOLD . "(!) " . C::RESET . C::YELLOW . "Usage: /deletehome <name>");
            return false;
        }

        if(Loader::getInstance()->getSqliteDatabase()->getHome($sender->getName(), (string)$args[0]) === null){
            $sender->sendMessage(C::RED . C::BOLD . "(!) " . C::RESET . C::RED . "The entered home name does not exist.");
            return false;
        }

        Loader::getInstance()->getSqliteDatabase()->removeHome($sender->getName(), (string)$args[0]);
        $sender->sendMessage(C::GREEN . C::BOLD . "(!) " . C::RESET . C::GREEN . $args[0] . " has been removed in your home list");
    }

    /**
     * @return Plugin
     */
    public function getOwningPlugin(): Plugin {
        return Loader::getInstance();
    }
}