<?php

namespace vale\sage\demonic\Trojan\command;

use CortexPE\Commando\args\RawStringArgument;
use CortexPE\Commando\args\TestArg;
use CortexPE\Commando\BaseCommand;
use pocketmine\command\CommandSender;
use vale\sage\demonic\Trojan\BanManager;
use vale\sage\demonic\Trojan\TrojanAPI;
use vale\sage\demonic\Loader;

class BlackList extends BaseCommand
{
    public function prepare(): void
    {
        $this->registerArgument(0,new TestArg("player",true));
        $this->registerArgument(1,new RawStringArgument("reason",true));
    }

    public function onRun(CommandSender $sender, string $aliasUsed, array $args): void
    {
        if(!(isset($args["player"]))){
            $sender->sendMessage("§cUsage: /blacklist §f<player> <reason>");
            return;
        }
        if(!(isset($args["reason"]))){
            $sender->sendMessage("§cUsage: /blacklist <player> §f<reason>");
            return;
        }
        $player = $args["player"];
        $reason = $args["reason"];
        $ban = new BanManager();
        $player_p = Loader::getInstance()->getServer()->getPlayerExact($player);
        if($player_p === null){
            $sender->sendMessage("§cPlayer is not online!");
            return;
        }
        if($ban->isBanned($player)){
            $sender->sendMessage("§cPlayer §f{$player} §cis already in the blacklist");
        } else {
            $ban->ban($player_p,$reason,$sender->getName(),true);
            $sender->sendMessage("§aPlayer §f{$player} §ais now in the blacklist");
            TrojanAPI::addLog($player, "none", $sender->getName(),"blacklist");
        }
    }
}