<?php

declare(strict_types = 1);

namespace vale\sage\demonic\commands\defaults\admin;

use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\utils\TextFormat as C;
use pocketmine\plugin\Plugin;
use pocketmine\plugin\PluginOwned;
use pocketmine\player\Player;
use vale\sage\demonic\Loader;

class ForceCommand extends Command implements PluginOwned {

    public function __construct() {
        parent::__construct("force", "Forcefully manage a player's homes.");
    }

    /**
     * @param CommandSender $sender
     * @param string $commandLabel
     * @param array $args
     * @return void
     */
    public function execute(CommandSender $sender, string $commandLabel, array $args) : void {
        if(!$sender->hasPermission("force.command")){
            $sender->sendMessage(C::RED . C::BOLD . "(!) " . C::RESET . C::RED . "No Permission to run this command");
            return;
        }

        if(count($args) <= 1){
            $sender->sendMessage(C::YELLOW . C::BOLD . "(!) " . C::RESET . C::YELLOW . "Usage: /force <delete:teleport:see> <player> <name>");
            return;
        }

        $player = Loader::getInstance()->getServer()->getPlayerByPrefix($args[1]);

        if($player === null){
            $sender->sendMessage(C::RED . C::BOLD . "(!) " . C::RESET . C::RED . "Player not found");
            return;
        }

        switch(strtolower($args[0])){
            case "delete":
            case "del":
                if(count($args) <= 2){
                    $sender->sendMessage(C::YELLOW . C::BOLD . "(!) " . C::RESET . C::YELLOW . "Usage: /force <delete:teleport:see> <player> <name>");
                    return;
                }

                if(Loader::getInstance()->getSqliteDatabase()->getHome($player->getName(), (string)$args[2]) === null){
                    $sender->sendMessage(C::RED . C::BOLD . "(!) " . C::RESET . C::RED . "The entered home name does not exist.");
                    return;
                }

                $player->chat("/deletehome " . (string)$args[2]);
                $sender->sendMessage(C::GREEN . C::BOLD . "(!) " . C::RESET . C::GREEN . $args[2] . " has been removed in " . $player->getName() . "'s home list");
                break;
            case "see":
                $sender->sendMessage(C::YELLOW . C::BOLD . "(!) " . C::RESET . C::YELLOW . $player->getName() . "'s current homes: " . implode(", ", Loader::getInstance()->getSqliteDatabase()->getHomes($player->getName())));
                break;
            case "teleport":
            case "tp":
                if(!$sender instanceof Player){
                    return;
                }

                if(count($args) <= 2){
                    $sender->sendMessage(C::YELLOW . C::BOLD . "(!) " . C::RESET . C::YELLOW . "Usage: /force <delete:teleport:see> <player> <name>");
                    return;
                }

                if(($pos = Loader::getInstance()->getSqliteDatabase()->getHome($player->getName(), (string)$args[2])) === null){
                    $sender->sendMessage(C::RED . C::BOLD . "(!) " . C::RESET . C::RED . "The entered home name does not exist.");
                    return;
                }

                $sender->teleport(Loader::getInstance()->stringToPosition($pos));
                break;
            default:
                $sender->sendMessage(C::YELLOW . C::BOLD . "(!) " . C::RESET . C::YELLOW . "Usage: /force <delete:teleport:see> <player> <name>");
            break;
        }
    }

    /**
     * @return Plugin
     */
    public function getOwningPlugin(): Plugin {
        return Loader::getInstance();
    }
}