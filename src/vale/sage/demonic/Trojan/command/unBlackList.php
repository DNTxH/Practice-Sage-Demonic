<?php

namespace vale\sage\demonic\Trojan\command;

use CortexPE\Commando\args\RawStringArgument;
use CortexPE\Commando\args\TestArg;
use CortexPE\Commando\BaseCommand;
use pocketmine\command\CommandSender;
use vale\sage\demonic\Trojan\BanManager;
use vale\sage\demonic\Trojan\TrojanAPI;

class unBlackList extends BaseCommand
{
    public function prepare(): void
    {
        $this->registerArgument(0,new RawStringArgument("player",true));
    }

    public function onRun(CommandSender $sender, string $aliasUsed, array $args): void
    {
        if(!(isset($args["player"]))){
            $sender->sendMessage("§cUsage: /unBlackList §7<player>");
            return;
        }
        $target = $args["player"];
        $ban = new BanManager();
        if($ban->isBanned($target)) {
            $unban = $ban->unBan($target,true);
            if($unban) {
                $sender->sendMessage("§aSuccessfully unban §e" . $target);
                TrojanAPI::addLog($target,"none",$sender->getName(),"unBlackList");
            } else {
                $sender->sendMessage("§c$target is not in blacklist!");
            }
        } else {
            $sender->sendMessage("§c$target is not in blacklist!");
        }
    }
}