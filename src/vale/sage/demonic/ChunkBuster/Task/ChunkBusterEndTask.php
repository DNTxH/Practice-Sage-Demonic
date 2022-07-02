<?php

namespace vale\sage\demonic\ChunkBuster\Task;

use vale\sage\demonic\ChunkBuster\Listener\ChunkBusterEventListener;
use pocketmine\player\Player;
use pocketmine\scheduler\Task;

class ChunkBusterEndTask extends Task
{
    private ChunkBusterEventListener $plugin;
    private Player $player;

    public function __construct(ChunkBusterEventListener $plugin,Player $player)
    {
        $this->plugin = $plugin;
        $this->player = $player;
    }

    public function onRun(): void
    {
        $this->plugin->removeLimit($this->player);
    }
}