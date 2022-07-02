<?php

declare(strict_types = 1);

namespace vale\sage\demonic\commands\defaults\teleport;

use vale\sage\demonic\Loader;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\utils\TextFormat as C;
use pocketmine\plugin\Plugin;
use pocketmine\plugin\PluginOwned;
use pocketmine\player\Player;

class TeleportCommand extends Command implements PluginOwned {

    public function __construct() {
        parent::__construct("teleport", "Teleport to another player.");
    }

    /**
     * @param CommandSender $sender
     * @param string $commandLabel
     * @param array $args
     * @return void
     */
    public function execute(CommandSender $sender, string $commandLabel, array $args) : void {
        if(!$sender instanceof Player){
            return;
        }

        if(!isset($args[0])){
            $sender->sendMessage(C::YELLOW . C::BOLD . "(!) " . C::RESET . C::YELLOW . "Usage: /tpa <player>");
            return;
        }

        $player = Loader::getInstance()->getServer()->getPlayerByPrefix($args[0]);

        if($player === null){
            $sender->sendMessage(C::RED . C::BOLD . "(!) " . C::RESET . C::RED . "Player not found");
            return;
        }

        if($player === $sender){
            $sender->sendMessage(C::RED . C::BOLD . "(!) " . C::RESET . C::RED . "You can't teleport to your self");
            return;
        }

        if(isset(Loader::getInstance()->tpa[$sender->getName()])){
            if(array_key_exists($player->getName(), Loader::getInstance()->tpa[$sender->getName()])){
                $time = Loader::getInstance()->tpa[$sender->getName()][$player->getName()];

                if(($sec = 30 - (time() - $time)) > 0){
                    $sender->sendMessage(C::RED . C::BOLD . "(!) " . C::RESET . C::RED . "Please wait " . $sec . " more seconds to ask teleport to " . $player->getName());
                    return;
                }
            }
        }

        $sender->sendMessage(C::GREEN . C::BOLD . "(!) " . C::RESET . C::GREEN . "Sent teleportation request to " . $player->getName());
        $player->sendMessage(C::YELLOW . C::BOLD . "(!) " . C::RESET . C::YELLOW . "You have 30 seconds to accept " . $sender->getName() . " teleportation request (/tpaaccept " . $sender->getName() . ")");
        Loader::getInstance()->tpa[$sender->getName()][$player->getName()] = time();
    }

    /**
     * @return Plugin
     */
    public function getOwningPlugin(): Plugin {
        return Loader::getInstance();
    }
}