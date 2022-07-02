<?php

declare(strict_types = 1);

namespace vale\sage\demonic\commands\defaults\homes;

use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\player\Player;
use pocketmine\utils\TextFormat as C;
use pocketmine\plugin\Plugin;
use pocketmine\plugin\PluginOwned;
use vale\sage\demonic\Loader;

class ListHomeCommand extends Command implements PluginOwned {

    public function __construct() {
        parent::__construct("listhomes", "List your current homes.");
    }

    /**
     * @param CommandSender $sender
     * @param string $commandLabel
     * @param array $args
     */
    public function execute(CommandSender $sender, string $commandLabel, array $args) : void {
        if(!$sender instanceof Player){
            return;
        }

        $sender->sendMessage(C::YELLOW . C::BOLD . "(!) " . C::RESET . C::YELLOW . "Your current homes: " . implode(", ", Loader::getInstance()->getSqliteDatabase()->getHomes($sender->getName())));
    }

    /**
     * @return Plugin
     */
    public function getOwningPlugin(): Plugin {
        return Loader::getInstance();
    }
}