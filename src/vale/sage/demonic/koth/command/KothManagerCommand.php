<?php namespace vale\sage\demonic\koth\command;

use vale\sage\demonic\Loader;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\player\Player;
use pocketmine\Server;

class KothManagerCommand extends Command {

    public function __construct() {
        parent::__construct("kmc", "Koth Manager Command");
    }

    public function execute(CommandSender $sender, string $commandLabel, array $args) : void {
        if (!($sender instanceof Player) or Server::getInstance()->isOp($sender->getName())) {
            $arg = array_shift($args);
            if ($arg == "") {
                $sender->sendMessage("args: /kmc start");
                return;
            }
            if ($arg == "start") {
                Loader::getKoth()->startKoth();
            }
        }
    }

}