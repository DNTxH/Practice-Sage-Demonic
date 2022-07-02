<?php

namespace vale\sage\demonic\commands\defaults;

use vale\sage\demonic\Loader;
use vale\sage\demonic\tasks\ClearLag;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\command\utils\CommandException;

class LagCommand extends Command
{

    public function __construct() {
        parent::__construct("lag", "Displays how many seconds left till all the entities clear.");
    }

    public function execute(CommandSender $sender, string $commandLabel, array $args) {
        if (isset($args[0])) {
            if ($sender->hasPermission("lag.clear")) {
                if ($args[0] == "clear") {
                    ClearLag::clearEntities();
                    return;
                }
            } else $sender->sendMessage(Loader::REG_CMD_PREFIX . ClearLag::$seconds . " seconds till all entities are cleared.");
            return;
        }
        $sender->sendMessage(Loader::REG_CMD_PREFIX . ClearLag::$seconds . " seconds till all entities are cleared.");
    }
}