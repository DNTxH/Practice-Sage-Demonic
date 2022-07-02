<?php

namespace vale\sage\demonic\Partner\Task;

use pocketmine\player\Player;
use pocketmine\scheduler\Task;
use pocketmine\world\Position;

class TeleportPlayer extends Task
{
    private Player $player;
    private Position $destination;
    private string $message;

    public function __construct(Player $player, Position $destination, String $message){
        $this->player = $player;
        $this->destination = $destination;
        $this->message = $message;
    }

    public function onRun(): void
    {
        $this->player->teleport($this->destination);
        $this->player->sendMessage($this->message);
    }
}