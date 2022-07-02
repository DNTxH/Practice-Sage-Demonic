<?php

namespace vale\sage\demonic\Trojan\command;

use CortexPE\Commando\args\RawStringArgument;
use CortexPE\Commando\BaseCommand;
use pocketmine\command\CommandSender;
use vale\sage\demonic\Trojan\MuteManager;
use vale\sage\demonic\Trojan\TrojanAPI;

class unMute extends BaseCommand
{
    public function prepare(): void
    {
        $this->registerArgument(0, new RawStringArgument("player",true));
    }

    public function onRun(CommandSender $sender, string $aliasUsed, array $args): void
    {
        if(!(isset($args["player"])))
        {
            $sender->sendMessage("§cUsage: /unmute §7<player>");
            return;
        }
        $player = $args["player"];
        $mute_manager = new MuteManager();
        if($mute_manager->isMute($player))
        {
            $mute_manager->removeMute($player);
            $sender->sendMessage("§aSuccessfully unmuted §e{$player}");
            TrojanAPI::addLog($player, "none", $sender->getName(),"unMute");
        } else {
            $sender->sendMessage("§c{$player} is not muted");
        }
    }
}