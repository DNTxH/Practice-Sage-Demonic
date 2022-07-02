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

class Mute extends BaseCommand
{
    public function Prepare(): void
    {
        $this->registerArgument(0, new TestArg("player", true));
        $this->registerArgument(1, new RawStringArgument("reason", true));
        $this->registerArgument(2, new BooleanArgument("broadcast message", true));
    }

    public function onRun(CommandSender $sender, string $aliasUsed, array $args): void
    {
        if(!(isset($args["player"])))
        {
            $sender->sendMessage("§cUsage: /mute §7<player> <reason> <broadcast message>");
            return;
        }
        if(!(isset($args["reason"])))
        {
            $sender->sendMessage("§cUsage: /mute <player> §7<reason> <broadcast message>");
            return;
        }
        if(!(isset($args["broadcast message"])))
        {
            $sender->sendMessage("§cUsage: /mute <player> <reason> §7<broadcast message>");
            return;
        }
        $player = $args["player"];
        $reason = $args["reason"];
        $broadcast = $args["broadcast message"];
        $sender->sendMessage("§aMuted §e{$player} §afor §e{$reason}");
        $mute_manager = new MuteManager();
        $mute_manager->setMute($player,$sender->getName(),null,$reason);
        if($broadcast)
        {
            $sender->getServer()->broadcastMessage("§c{$player} §ahas been muted for §c{$reason} §aby §e{$sender->getName()}");
        } else {
            $staffManager = new StaffManager();
            foreach(Loader::getInstance()->getServer()->getOnlinePlayers() as $p) {
                if ($staffManager->isInStaffMode($p)) {
                    $p->sendMessage("§c{$player} §ahas been muted for §c{$reason} §aby §e{$sender->getName()}");
                }
            }
        }
        TrojanAPI::addLog($player, $reason, $sender->getName(),"mute");
    }
}