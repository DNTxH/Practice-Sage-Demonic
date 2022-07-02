<?php

declare(strict_types = 1);

namespace vale\sage\demonic\commands\defaults\teleport;

use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use vale\sage\demonic\tasks\types\TeleportationTask;
use pocketmine\plugin\Plugin;
use pocketmine\plugin\PluginOwned;
use vale\sage\demonic\Loader;
use pocketmine\player\Player;
use pocketmine\utils\TextFormat as C;

class TeleportAcceptCommand extends Command implements PluginOwned {

    public function __construct() {
        parent::__construct("tpaccept", "Accept a teleport request.");
    }

    /**
     * @param CommandSender $sender
     * @param string $commandLabel
     * @param array $args
     * @return void
     */
    public function execute(CommandSender $sender, string $commandLabel, array $args) : void {
        if(!$sender instanceof Player) {
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

        $timer = 7;
        $exp = 0;

        if($sender->getXpManager()->getCurrentTotalXp() >= 1000){
            $sender->getXpManager()->subtractXp(1000);
            $timer = 0;
        } elseif($sender->getXpManager()->getCurrentTotalXp() > 0){
            $exp = $sender->getXpManager()->getCurrentTotalXp();
            $timer = 7 - round(142 / $exp);
            $sender->getXpManager()->subtractXp($exp);
        }

        Loader::getInstance()->getScheduler()->scheduleRepeatingTask(new TeleportationTask(Loader::getInstance(), $player, $sender->getPosition(), $exp, $timer), 20);
        $sender->sendMessage(C::GREEN . C::BOLD . "(!) " . C::RESET . C::GREEN . $player->getName() ." is teleporting To You");
        $player->sendMessage(C::GREEN . C::BOLD . "(!) " . C::RESET . C::GREEN . $sender->getName() . " accepted your teleportation request!");
        $player->sendMessage(C::GREEN . C::BOLD . "(!) " . C::RESET . C::GREEN . "Teleporting To " . $sender->getName());
        unset(Loader::getInstance()->tpa[$player->getName()][$sender->getName()]);
    }

    /**
     * @return Plugin
     */
    public function getOwningPlugin(): Plugin {
        return Loader::getInstance();
    }
}