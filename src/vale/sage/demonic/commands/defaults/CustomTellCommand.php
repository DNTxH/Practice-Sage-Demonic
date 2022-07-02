<?php

namespace vale\sage\demonic\commands\defaults;

use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\player\Player;
use pocketmine\Server;
use pocketmine\utils\TextFormat;

class CustomTellCommand extends Command {

    public function __construct() {
        parent::__construct("tell", "Private Message", "", ["msg"]);
    }

    public function execute(CommandSender $sender, string $commandLabel, array $args) {
        if ($sender instanceof Player) {
            $player = array_shift($args);
            if ($player == "") {
                $sender->sendMessage(TextFormat::RED . "That player cannot be found");
                return;
            }
            $player = Server::getInstance()->getPlayerByPrefix($player);
            if ($player == null) {
                $sender->sendMessage(TextFormat::RED . "That player cannot be found");
                return;
            }
            $message = join(" ", $args);
            if ($message == "") {
                $sender->sendMessage("Please provide a message to send to the player");
                return;
            }
            $sender->sendMessage(TextFormat::YELLOW . "§8(§d§lMSG SENT§r§8) §7-> " . $player->getDisplayName() . TextFormat::RESET . "§7 " . implode(" ", $args));
            $player->sendMessage(TextFormat::YELLOW . "§8(§d§lMSG RECIEVED§r§8) §7-> " . $sender->getDisplayName() . TextFormat::RESET . "§7 " . implode(" ", $args));
        }
    }

}