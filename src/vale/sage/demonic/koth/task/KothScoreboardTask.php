<?php namespace vale\sage\demonic\koth\task;

use vale\sage\demonic\koth\Koth;
use vale\sage\demonic\Loader;
use pocketmine\scheduler\Task;
use pocketmine\Server;

class KothScoreboardTask extends Task {

    public function onRun() : void {
        $running = Loader::getKoth()->running;
        if (!$running) $this->getHandler()->cancel();
        foreach(Server::getInstance()->getOnlinePlayers() as $player) {
            if (!$player->getWorld()->getFolderName() == Koth::WORLD) continue;
            Loader::getKoth()->sendKothScoreboard($player);
        }
    }

}