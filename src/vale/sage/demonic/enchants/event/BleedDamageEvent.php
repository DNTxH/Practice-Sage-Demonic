<?php

declare(strict_types = 1);

namespace vale\sage\demonic\enchants\event;

use pocketmine\event\Cancellable;
use pocketmine\event\CancellableTrait;
use pocketmine\event\player\PlayerEvent;
use pocketmine\player\Player;

class BleedDamageEvent extends PlayerEvent implements Cancellable {
    use CancellableTrait;

    /** @var float  */
    private float $damage;

    /**
     * @param Player $player
     * @param float $damage
     */
    public function __construct(Player $player, float $damage) {
        $this->player = $player;
        $this->damage = $damage;
    }

    /**
     * @return float
     */
    public function getDamage() : float {
        return $this->damage;
    }
}