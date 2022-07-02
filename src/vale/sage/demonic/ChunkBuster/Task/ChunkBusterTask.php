<?php

namespace vale\sage\demonic\ChunkBuster\Task;

use pocketmine\block\Block;
use pocketmine\block\BlockFactory;
use pocketmine\scheduler\Task;

class ChunkBusterTask extends Task {

    private Block $block;

    public function __construct(Block $block) {
        $this->block = $block;
    }


    public function onRun(): void
    {
        $block = $this->block;
        $world = $block->getPosition()->getWorld();
        $world->setBlock($block->getPosition(),BlockFactory::getInstance()->get(0,0));
    }
}
