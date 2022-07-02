<?php

namespace vale\sage\demonic\Trojan\command;

use CortexPE\Commando\args\RawStringArgument;
use CortexPE\Commando\BaseCommand;
use pocketmine\command\CommandSender;
use pocketmine\player\Player;
use vale\sage\demonic\Trojan\TrojanAPI;

class SetAC extends BaseCommand
{
    public function prepare(): void
    {
        $this->registerArgument(0,new RawStringArgument("on/off",true));
    }

    public function onRun(CommandSender $sender, string $aliasUsed, array $args): void
    {
        if(!(isset($args["on/off"]))){
            $sender->sendMessage("§cUsage: /setac <on/off>");
            return;
        }
        if(strtolower($args["on/off"]) !== "on" && strtolower($args["on/off"]) !== "off") {
            $sender->sendMessage("§aUsage: /setac <on/off>");
            return;
        }
        if($sender instanceof Player) {
            if(strtolower($args["on/off"]) == "on"){
                $set = true;
            } else {
                $set = false;
            }
            if (TrojanAPI::setAlert($sender, $set)) {
                $sender->sendMessage("§aSet AC to " . $args["on/off"]);
            } else {
                $sender->sendMessage("§cStaff can't set AC");
            }
        } else {
            $sender->sendMessage("§cThis command can only be used in-game.");
        }
    }
}