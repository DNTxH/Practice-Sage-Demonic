<?php

namespace vale\sage\demonic\Trojan\command;

use CortexPE\Commando\args\RawStringArgument;
use CortexPE\Commando\args\TestArg;
use CortexPE\Commando\BaseCommand;
use pocketmine\command\CommandSender;
use vale\sage\demonic\Trojan\TrojanAPI;
use vale\sage\demonic\Loader;

class warn extends BaseCommand
{
    public function prepare(): void
    {
        $this->registerArgument(0,new TestArg("player",true));
        $this->registerArgument(1,new RawStringArgument("reason",true));
    }

    public function onRun(CommandSender $sender, string $aliasUsed, array $args): void
    {
        if(!(isset($args["player"]))){
            $sender->sendMessage("§cUsage: /warn §f<player> <reason>");
            return;
        }
        if(!(isset($args["reason"]))){
            $sender->sendMessage("§cUsage: /warn <player> §f<reason>");
            return;
        }
        $player = $args["player"];
        $reason = $args["reason"];
        $sender->sendMessage("§aWarned §f{$player} §afor §f{$reason}");
        TrojanAPI::addWarn($player,$reason,$sender->getName());
        if(Loader::getInstance()->getServer()->getPlayerExact($player)){
            Loader::getInstance()->getServer()->getPlayerExact($player)->sendMessage("§cYou have been warned for §f{$reason}");
        } else {
            $sender->sendMessage("§cPlayer is not online");
        }
    }
}