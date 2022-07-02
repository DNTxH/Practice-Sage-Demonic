<?php

namespace vale\sage\demonic\commands\defaults\staff;

use vale\sage\demonic\Loader;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\player\Player;
use pocketmine\Server;

class StaffModeCommand extends Command {

    public function __construct() {
        parent::__construct("staffmode", "StaffMode Command", "", []);
    }

    public function execute(CommandSender $sender, string $commandLabel, array $args) : void {
        if ($sender instanceof Player) {
            if (Server::getInstance()->isOp($sender->getName())) {
                if (!Loader::getStaffManager()->isInStaffMode($sender)) {
                    Loader::getStaffManager()->setInStaffMode($sender);
                    $sender->sendMessage("§aYou have entered staff mode.");
                } else {
                    Loader::getStaffManager()->unsetFromStaffMode($sender);
                    $sender->sendMessage("§aYou are no longer in staff mode.");
                }
            } else {
                $sender->sendMessage("§cMissing Permissions.");
            }
        }
    }

}