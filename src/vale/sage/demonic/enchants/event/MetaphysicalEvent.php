<?php

declare(strict_types = 1);

namespace vale\sage\demonic\enchants\event;

use pocketmine\event\Cancellable;
use pocketmine\event\CancellableTrait;
use pocketmine\event\player\PlayerEvent;
use pocketmine\player\Player;

class MetaphysicalEvent extends PlayerEvent implements Cancellable {
    use CancellableTrait;

    /**
     * @param Player $player
     */
    public function __construct(Player $player) {
        $this->player = $player;
    }
}