<?php

namespace vale\sage\demonic\Trojan\Task;

use pocketmine\scheduler\Task;
use vale\sage\demonic\Trojan\MuteManager;
use vale\sage\demonic\Loader;

class MuteTask extends Task
{
    public function onRun(): void
    {
        $mute_manager = new MuteManager();
        $muted_player = $mute_manager->getMuteList();
        foreach ($muted_player as $player=>$data) {
            if($mute_manager->getMuteTimeLeft($player) !== false) {
                if($mute_manager->getMuteTimeLeft($player) <= 0) {
                    $mute_manager->removeMute($player);
                    if(Loader::getInstance()->getServer()->getPlayerExact($player)){
                        Loader::getInstance()->getServer()->getPlayerExact($player)->sendMessage("§cYou're unmuted!");
                    }
                }
            }
        }
    }
}