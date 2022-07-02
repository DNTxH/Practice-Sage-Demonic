<?php

namespace vale\sage\demonic\staff;

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
                $staffClass = new StaffManager();
                if ($staffClass->isInStaffMode($sender) === false) {
                    $staffClass->setInStaffMode($sender);
                    $sender->sendMessage("§aYou have entered staff mode.");
                } else {
                    $staffClass->unsetFromStaffMode($sender);
                    $sender->sendMessage("§aYou are no longer in staff mode.");
                }
            } else {
                $sender->sendMessage("§cMissing Permissions.");
            }
        }
    }

}