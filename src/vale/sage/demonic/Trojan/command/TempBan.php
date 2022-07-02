<?php

namespace vale\sage\demonic\Trojan\command;

use CortexPE\Commando\args\BooleanArgument;
use CortexPE\Commando\args\RawStringArgument;
use CortexPE\Commando\args\TestArg;
use CortexPE\Commando\BaseCommand;
use pocketmine\command\CommandSender;
use vale\sage\demonic\staff\StaffManager;
use vale\sage\demonic\Trojan\BanManager;
use vale\sage\demonic\Trojan\TrojanAPI;
use vale\sage\demonic\Loader;

class TempBan extends BaseCommand
{
    public function prepare(): void
    {
        $this->registerArgument(0,new TestArg("player",true));
        $this->registerArgument(1,new RawStringArgument("time",true));
        $this->registerArgument(2,new RawStringArgument("reason",true));
        $this->registerArgument(3,new RawStringArgument("(-s)",true));
    }

    public function onRun(CommandSender $sender, string $aliasUsed, array $args): void
    {
        if(!(isset($args["player"]))) {
            $sender->sendMessage("§cUsage: /tempban §7<player> <time> <reason> (-s)");
            return;
        }
        if(!(isset($args["time"]))) {
            $sender->sendMessage("§cUsage: /tempban <player> §7<time> <reason> (-s)");
            return;
        }
        if(!(isset($args["reason"]))) {
            $sender->sendMessage("§cUsage: /tempban <player> <time> §7<reason> (-s)");
            return;
        }
        $target = $args["player"];
        $time = $args["time"];
        $reason = $args["reason"];
        $time = preg_split('#(?<=\d)(?=[a-z])#i', $time);
        if(count($time) == 1){
            $sender->sendMessage("Some error at <time>");
            return;
        }
        $time_format = $time[1];
        $time = $time[0];
        $allow_time_type = array(
            "m" => "minute",
            "min" => "minute",
            "minute" => "minute",
            "h" => "hour",
            "hour" => "hour",
            "d" => "day",
            "day" => "day",
            "w" => "week",
            "week" => "week",
            "mon" => "month",
            "month" => "month",
            "y" => "year",
            "year" => "year",
        );
        if(in_array($time_format,array_keys($allow_time_type))) {
            $date = self::addTime($allow_time_type[$time_format],$time);
            $ban = new BanManager();
            if($ban->isBanned($target)) {
                $sender->sendMessage("§cPlayer is already banned");
            } else {
                if(Loader::getInstance()->getServer()->getPlayerExact($target) === null){
                    $sender->sendMessage("§cPlayer is not online");
                    return;
                }
                $player_p = Loader::getInstance()->getServer()->getPlayerExact($target);
                $ban->ban($player_p,$reason,$sender->getName(),false,$date);
                if(!(isset($args["(-s)"]))) {
                    Loader::getInstance()->getServer()->broadcastMessage("§4(!) $target §7has been temporary banned by §c§l". $sender->getName() ." §r§7for §c".$time." ".$allow_time_type[$time_format]);
                } else {
                    $staffManager = new StaffManager();
                    foreach(Loader::getInstance()->getServer()->getOnlinePlayers() as $p) {
                        if($staffManager->isInStaffMode($p)) {
                            $p->sendMessage("§7(§8Slient§7) §4$target §7has been temporary banned by §c§l" . $sender->getName() . " §r§7for §ctime $time" . $allow_time_type[$time_format]);
                        }
                    }
                    Loader::getInstance()->getServer()->getLogger()->info("§7(§8Slient§7) §4$target §7has been temporary banned by §c§l".$sender->getName()." §r§7for §ctime $time".$allow_time_type[$time_format]);
                }
                TrojanAPI::addLog($target, $reason, $sender->getName(),"tempBan");
            }
            return;
        }
    }

    public static function addTime(string $type,int $int){
        $date = new \DateTime("now");
        // add three days
        $date->modify("+$int $type");
        return $date;
    }
}