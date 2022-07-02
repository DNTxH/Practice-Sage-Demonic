<?php

namespace vale\sage\demonic\levels\task;

use vale\sage\demonic\GenesisPlayer;
use vale\sage\demonic\Loader;
use pocketmine\item\VanillaItems;
use pocketmine\scheduler\Task;
use pocketmine\utils\TextFormat;
use vale\sage\demonic\levels\PlayerLevelUtils;

class CheckLevelUpTask extends Task {

    public function onRun(): void {
        foreach(Loader::getInstance()->getServer()->getOnlinePlayers() as $player) {
            if(!$player instanceof GenesisPlayer) continue;
            if(PlayerLevelUtils::calculateLevelXpRequirement($player) === -1) continue;

            if($player->getLevelExperience() >= PlayerLevelUtils::calculateLevelXpRequirement($player)) {
                $player->sendMessage(TextFormat::GREEN . "You have levelled up and received 16 diamonds and 1 talent point!");
                $player->resetLevelExperience();
                $player->increaseTalentPoints();
                $player->increaseLevel();
                $player->getInventory()->addItem(VanillaItems::DIAMOND()->setCount(16));
            }
        }
    }
}