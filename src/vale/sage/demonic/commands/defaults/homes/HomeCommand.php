<?php

declare(strict_types = 1);

namespace vale\sage\demonic\commands\defaults\homes;

use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use vale\sage\demonic\tasks\types\TeleportationTask;
use pocketmine\player\Player;
use pocketmine\plugin\Plugin;
use pocketmine\plugin\PluginOwned;
use vale\sage\demonic\Loader;

class HomeCommand extends Command implements PluginOwned {

    public function __construct() {
        parent::__construct("home", "Teleport to a home.");
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
            $sender->sendMessage(C::YELLOW . C::BOLD . "(!) " . C::RESET . C::YELLOW . "Usage: /home <name>");
            return;
        }

        if(($pos = Loader::getInstance()->getSqliteDatabase()->getHome($sender->getName(), $args[0])) === null){
            $sender->sendMessage(C::RED . C::BOLD . "(!) " . C::RESET . C::RED . "The entered home name does not exist.");
            return;
        }

        $timer = 7;
        $exp = 0;

        if($sender->getXpManager()->getCurrentTotalXp() >= 1000){
            $sender->getXpManager()->subtractXp(1000);
            $timer = 0;
        } elseif ($sender->getXpManager()->getCurrentTotalXp() > 0){
            $exp = $sender->getXpManager()->getCurrentTotalXp();
            $timer = 7 - round(142 / $exp);
            $exp = round(142 / $exp);
            $sender->getXpManager()->subtractXp((int)$exp);
        }

        Loader::getInstance()->getScheduler()->scheduleRepeatingTask(new TeleportationTask(Loader::getInstance(), $sender, Loader::getInstance()->stringToPosition($pos), $exp, $timer), 20);
    }

    /**
     * @return Plugin
     */
    public function getOwningPlugin(): Plugin {
        return Loader::getInstance();
    }
}