<?php

namespace vale\sage\demonic\levels;

use vale\sage\demonic\GenesisPlayer;

class PlayerLevelUtils {

    /**
     * @param GenesisPlayer $player
     * @return int
     */
    public static function calculateLevelXpRequirement(GenesisPlayer $player) : int {
        if($player->getLevel() >= 100) return -1;

        $firstTen = [1 => 75, 2 => 125, 3 => 180, 4 => 260, 5 => 330, 6 => 400, 7 => 480, 8 => 590, 9 => 705, 10 => 800];

        return $firstTen[$player->getLevel()] ?? round((($player->getLevel() / 2) ^ 3) * 4);
    }

}