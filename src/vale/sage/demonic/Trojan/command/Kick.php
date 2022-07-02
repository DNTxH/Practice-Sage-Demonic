<?php

namespace vale\sage\demonic\Trojan\command;

use CortexPE\Commando\args\BooleanArgument;
use CortexPE\Commando\args\RawStringArgument;
use CortexPE\Commando\args\TestArg;
use CortexPE\Commando\BaseCommand;
use pocketmine\command\CommandSender;
use vale\sage\demonic\staff\StaffManager;
use vale\sage\demonic\Trojan\TrojanAPI;
use vale\sage\demonic\Loader;

class Kick extends BaseCommand
{
    public function prepare(): void
    {
        $this->registerArgument(0,new TestArg("player",true));
        $this->registerArgument(1,new RawStringArgument("reason",true));
        $this->registerArgument(2,new BooleanArgument("broadcast message",true));
    }

    public function onRun(CommandSender $sender, string $aliasUsed, array $args): void
    {
        if(!(isset($args["player"]))){
            $sender->sendMessage("§cUsage: /kick §7<player> <reason> <broadcast message>");
            return;
        }
        if(!(isset($args["reason"]))){
            $sender->sendMessage("§cUsage: /kick <player>§7 <reason> <broadcast message>");
            return;
        }
        if(!(isset($args["broadcast message"]))){
            $sender->sendMessage("§cUsage: /kick <player> <reason> §7<broadcast message>");
            return;
        }
        $player = $args["player"];
        $reason = $args["reason"];
        if(Loader::getInstance()->getServer()->getPlayerExact($player) !== null) {
            Loader::getInstance()->getServer()->getPlayerExact($player)->kick($reason);
            if ($args["broadcast message"]) {
                Loader::getInstance()->getServer()->broadcastMessage("§c" . $player . " was kicked for " . $reason);
            } else {
                $staffManager = new StaffManager();
                foreach(Loader::getInstance()->getServer()->getOnlinePlayers() as $p) {
                    if($staffManager->isInStaffMode($p)){
                        $p->sendMessage("§c" . $player . " §7has been kicked for §c" . $reason);
                    }
                }
            }
        } else {
            $sender->sendMessage("§cPlayer §7" . $player . " §cis not online");
        }
        TrojanAPI::addLog($player, $reason, $sender->getName(),"kick");

    }
}