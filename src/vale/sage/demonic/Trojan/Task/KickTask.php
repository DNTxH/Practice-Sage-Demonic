<?php

namespace vale\sage\demonic\Trojan\Task;

use pocketmine\player\Player;
use pocketmine\scheduler\Task;

class KickTask extends Task
{
    private Player $player;

    public function __construct(Player $player){
        $this->player = $player;
    }

    public function onRun(): void
    {
        $this->player->kick("§c你的帳號已被管理員禁止登入");
    }
}