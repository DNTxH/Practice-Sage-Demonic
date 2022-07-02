<?php namespace vale\sage\demonic\koth\command;

use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\player\Player;
use pocketmine\Server;

class CurrentPositionCommand extends Command {

    public function __construct() {
        parent::__construct("cps", "Child Protective Services.");
    }

    public function execute(CommandSender $sender, string $commandLabel, array $args) : void {
        if ($sender instanceof Player) {
            $pos = $sender->getPosition()->asVector3();
            $sender->sendMessage($pos->getFloorX(). ":".$pos->getFloorY().":". $pos->getFloorZ());
        }
    }

}