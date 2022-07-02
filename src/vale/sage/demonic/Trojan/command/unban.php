<?php

namespace vale\sage\demonic\Trojan\command;

use CortexPE\Commando\args\RawStringArgument;
use CortexPE\Commando\BaseCommand;
use pocketmine\command\CommandSender;
use vale\sage\demonic\Trojan\BanManager;
use vale\sage\demonic\Trojan\TrojanAPI;
use vale\sage\demonic\Loader;

class unban extends BaseCommand
{
    public function prepare(): void{
        $this->registerArgument(0, new RawStringArgument("player",true));
    }

    public function onRun(CommandSender $sender, string $aliasUsed, array $args): void
    {
        if(!(isset($args["player"]))){
            $sender->sendMessage("§c Usage: /unban §7<player>");
            return;
        }
        $player = $args["player"];
        $ban = new BanManager();
        if($ban->isBanned($player)) {
            $unban = $ban->unBan($player);
            if($unban) {
                $sender->sendMessage("§c$player §fhas been unbanned!");
                TrojanAPI::addLog($player, "none", $sender->getName(),"unban");
            } else {
                $sender->sendMessage("§c$player §fis not banned!");
            }
        } else {
            $sender->sendMessage("§c$player §fis not banned!");
        }
    }
}