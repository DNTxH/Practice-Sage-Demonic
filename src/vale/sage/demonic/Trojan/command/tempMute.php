<?php

namespace vale\sage\demonic\Trojan\command;

use CortexPE\Commando\args\BooleanArgument;
use CortexPE\Commando\args\RawStringArgument;
use CortexPE\Commando\args\TestArg;
use CortexPE\Commando\BaseCommand;
use pocketmine\command\CommandSender;
use vale\sage\demonic\staff\StaffManager;
use vale\sage\demonic\Trojan\MuteManager;
use vale\sage\demonic\Trojan\TrojanAPI;
use vale\sage\demonic\Loader;

class tempMute extends BaseCommand
{
    public function Prepare(): void
    {
        $this->registerArgument(0, new TestArg("player", true));
        $this->registerArgument(1, new RawStringArgument("reason", true));
        $this->registerArgument(2, new RawStringArgument("time", true));
        $this->registerArgument(3, new BooleanArgument("broadcast message", true));
    }

    public function onRun(CommandSender $sender, string $aliasUsed, array $args): void
    {
        if(!(isset($args["player"])))
        {
            $sender->sendMessage("§cUsage: /mute §7<player> <reason> <time> <broadcast message>");
            return;
        }
        if(!(isset($args["reason"])))
        {
            $sender->sendMessage("§cUsage: /mute <player> §7<reason> <time> <broadcast message>");
            return;
        }
        if(!(isset($args["time"])))
        {
            $sender->sendMessage("§cUsage: /mute <player> <reason> §7<time> <broadcast message>");
            return;
        }
        if(!(isset($args["broadcast message"])))
        {
            $sender->sendMessage("§cUsage: /mute <player> <reason> <time> §7<broadcast message>");
            return;
        }
        $player = $args["player"];
        $reason = $args["reason"];
        $time = $args["time"];
        $time = preg_split('#(?<=\d)(?=[a-z])#i', $time);
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
        $broadcast = $args["broadcast message"];
        if(in_array($time_format,array_keys($allow_time_type))) {
            $date = TempBan::addTime($allow_time_type[$time_format],$time);
            $MuteManager = new MuteManager();
            $MuteManager->setMute($player,$sender->getName(),$date,$reason);
            if($broadcast) {
                $sender->getServer()->broadcastMessage("§7{$player} §7has been muted for §c$time"."$allow_time_type[$time_format]"." §7for §c{$reason}");
            } else {
                $staffManager = new StaffManager();
                foreach(Loader::getInstance()->getServer()->getOnlinePlayers() as $p) {
                    if ($staffManager->isInStaffMode($p)) {
                        $p->sendMessage("§7{$player} §7has been muted for §c$time"."$allow_time_type[$time_format]"." §7for §c{$reason}");
                    }
                }
            }
            $sender->sendMessage("§7You muted{$player} §7for §c$time"."$allow_time_type[$time_format]"." §7for §c{$reason}");
            TrojanAPI::addLog($player, $reason, $sender->getName(),"tempMute");
        }
    }
}