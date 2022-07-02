<?php

declare(strict_types = 1);

namespace vale\sage\demonic\tasks\types;

use pocketmine\scheduler\Task;
use vale\sage\demonic\GenesisPlayer;
use vale\sage\demonic\Loader;

class PlayTimeUpdateTask extends Task {

    public function onRun(): void {
        foreach(Loader::getInstance()->getServer()->getOnlinePlayers() as $player) {
            if(!$player instanceof GenesisPlayer) continue;

            $player->increasePlayTime();

            if($player->getPlayTime() >= 86400) {
                $player->resetPlayTime();
            }
        }
    }

}