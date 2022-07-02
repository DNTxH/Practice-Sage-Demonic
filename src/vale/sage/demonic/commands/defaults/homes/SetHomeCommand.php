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
use pocketmine\world\Position;

class SetHomeCommand extends Command implements PluginOwned {

    public function __construct() {
        parent::__construct("sethome", "Set a home at your current position.");
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
            $sender->sendMessage(C::YELLOW . C::BOLD . "(!) " . C::RESET . C::YELLOW . "Usage: /sethome <name>");
            return;
        }

        Loader::getInstance()->getSqliteDatabase()->addHome($sender->getName(), (string)$args[0], Loader::getInstance()->positionToString(Position::fromObject($sender->getPosition()->floor(), $sender->getWorld())));
        $sender->sendMessage(C::GREEN . C::BOLD . "(!) " . C::RESET . C::GREEN . $args[0] . " set at your current Location");
    }

    /**
     * @return Plugin
     */
    public function getOwningPlugin(): Plugin {
        return Loader::getInstance();
    }
}