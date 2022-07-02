<?php

namespace vale\sage\demonic\Trojan\command;

use CortexPE\Commando\args\RawStringArgument;
use CortexPE\Commando\args\TestArg;
use CortexPE\Commando\BaseCommand;
use pocketmine\command\CommandSender;
use vale\sage\demonic\staff\StaffManager;
use vale\sage\demonic\Trojan\BanManager;
use vale\sage\demonic\Trojan\TrojanAPI;
use vale\sage\demonic\Loader;

class Ban extends BaseCommand
{

    public function prepare(): void
    {
        $this->registerArgument(0, new TestArg("player", true));
        $this->registerArgument(1, new RawStringArgument("reason", true));
        $this->registerArgument(2, new RawStringArgument("(-s)", true));
    }

   public function onRun(CommandSender $sender, string $aliasUsed, array $args):void{
        if(!(isset($args["player"]))){
            $sender->sendMessage("§cUsage /ban §7<player> <reason> (-s)");
            return;
        }
       if(!(isset($args["reason"]))){
           $sender->sendMessage("§cUsage /ban <player> §7<reason> (-s)");
           return;
       }
       $target = $args["player"];
        $reason = $args["reason"];
        $ban = new BanManager();
        if($ban->isBanned($target)){
            $sender->sendMessage("§c$target is already banned!");
            return;
        }
        $target_p = Loader::getInstance()->getServer()->getPlayerExact($target);
        if($target_p){
            $ban->ban($target_p,$reason,$sender->getName());
        } else {
            $sender->sendMessage("§c$target is not online!");
            return;
        }
        if(!(isset($args["(-s)"]))){
            Loader::getInstance()->getServer()->broadcastMessage("§4(!) $target §7has been Banned by §c§l". $sender->getName() . " forever");
        } else {
            $staffClass = new StaffManager();
            foreach(Loader::getInstance()->getServer()->getOnlinePlayers() as $player){
                if($staffClass->isInStaffMode($player)){
                    $player->sendMessage("§7(§8Slient§7) §4$target §7has been banned by §c§l".$sender->getName()." §r§7forever");
                }
            }
            Loader::getInstance()->getLogger()->info("§7(§8Slient§7) §4$target §7has been banned by §c§l".$sender->getName()." §r§7forever");
        }
        TrojanAPI::addLog($target, $reason, $sender->getName(),"ban");
   }
}