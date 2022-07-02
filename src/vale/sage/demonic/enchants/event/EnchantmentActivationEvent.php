<?php

declare(strict_types = 1);

namespace vale\sage\demonic\enchants\event;

use pocketmine\event\Cancellable;
use pocketmine\event\CancellableTrait;
use pocketmine\event\player\PlayerEvent;
use pocketmine\player\Player;

class EnchantmentActivationEvent extends PlayerEvent implements Cancellable {
    use CancellableTrait;

    /** @var Player|null */
    private ?Player $victim;

    /** @var bool */
    private bool $check = false;

    /**
     * @param Player $player
     * @param Player|null $victim
     */
    public function __construct(Player $player, ?Player $victim, bool $check = false) {
        $this->player = $player;
        $this->victim = $victim;
        $this->check = $check;
    }

    /**
     * @return Player|null
     */
    public function getVictim() : ?Player {
        return $this->player;
    }

    /**
     * @return bool
     */
    public function shouldCheck() : bool {
        return $this->check;
    }
}