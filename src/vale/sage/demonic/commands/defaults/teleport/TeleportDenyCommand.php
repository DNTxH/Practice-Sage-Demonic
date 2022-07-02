<?php

declare(strict_types = 1);

namespace vale\sage\demonic\commands\defaults\teleport;

use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\player\Player;
use pocketmine\plugin\Plugin;
use pocketmine\plugin\PluginOwned;
use vale\sage\demonic\Loader;
use pocketmine\utils\TextFormat as C;

class TeleportDenyCommand extends Command implements PluginOwned {

    public function __construct() {
        parent::__construct("tpdeny", "Deny a telport request.");
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

        if(!isset(Loader::getInstance()->tpa[$player->getName()]) || !array_key_exists($sender->getName(), Loader::getInstance()->tpa[$player->getName()]) || (30 - (time() - Loader::getInstance()->tpa[$player->getName()][$sender->getName()]) <= 0)){
            $sender->sendMessage(C::RED . C::BOLD . "(!) " . C::RESET . C::RED . "You do not have any active requests from the following user.");
            return;
        }

        $sender->sendMessage(C::GREEN . C::BOLD . "(!) " . C::RESET . C::GREEN . "Declined " . $player->getName() . " teleportation requests!");
        $player->sendMessage(C::RED . C::BOLD . "(!) " . C::RESET . C::RED . $sender->getName() . " declined your teleportation request!");
        unset(Loader::getInstance()->tpa[$player->getName()][$sender->getName()]);
    }

    /**
     * @return Plugin
     */
    public function getOwningPlugin(): Plugin {
        return Loader::getInstance();
    }
}