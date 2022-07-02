<?php namespace vale\sage\demonic\slotbot\commands;

use vale\sage\demonic\Loader;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\player\Player;

class SlotBotCommand extends Command {

    public function __construct() {
        parent::__construct("slotbot", "Open the slotbot menu.");
    }

    public function execute(CommandSender $sender, string $commandLabel, array $args) : void {
        if ($sender instanceof Player) {
            Loader::getSlotBotManager()->openMenuForPlayer($sender);
        }
    }

}