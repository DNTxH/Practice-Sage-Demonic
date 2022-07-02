<?php

namespace vale\sage\demonic\Trojan\Task;

use pocketmine\player\Player;
use pocketmine\scheduler\Task;

class AntiGlitchTask extends Task
{
    private Player $player;

    private int $direction;

    private bool $first = true;

    public function __construct(Player $player, int $direction) {
        $this->player = $player;
        $this->direction = $direction;
    }

    public function onRun(): void {
        if($this->first) {
            $this->getHandler()->cancel();
            $player = $this->player;
            if ($player->isOnline()) {
                // echo "NO GLITCH ACTIVATED \n";
                switch ($this->direction) {
                    case 1:
                    case 3:
                    case 0:
                    case 2:
                        $direction = $player->getDirectionVector();
                        $player->knockBack(0, $direction->getX() - $direction->getZ(),0.5);
                        break;
                }
            }
        }

        $this->first = false;
    }
}