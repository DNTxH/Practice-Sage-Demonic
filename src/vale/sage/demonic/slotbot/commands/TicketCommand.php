<?php namespace vale\sage\demonic\slotbot\commands;

use vale\sage\demonic\Loader;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\player\Player;
use pocketmine\Server;

class TicketCommand extends Command {

    public function __construct() {
        parent::__construct("ticket", "Give yourself a ticket.");
    }

    public function execute(CommandSender $sender, string $commandLabel, array $args) : void {
        if ($sender instanceof Player) {
            if (Server::getInstance()->isOp($sender->getName())) {
                Loader::getSlotBotManager()->giveTicket($sender);
                $sender->sendMessage("You have received a ticket.");
            }
        }
    }

}