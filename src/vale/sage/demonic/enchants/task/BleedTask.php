<?php

declare(strict_types = 1);

namespace vale\sage\demonic\enchants\task;

use pocketmine\player\Player;
use pocketmine\scheduler\Task;
use pocketmine\world\particle\RedstoneParticle;
use vale\sage\demonic\enchants\enchantments\type\unique\DeepWounds;
use vale\sage\demonic\enchants\event\BleedDamageEvent;

class BleedTask extends Task {

    /** @var int */
    private int $runs = 0;

    /** @var Player */
    private Player $player;

    /**
     * @param Player $player
     */
    public function __construct(Player $player) {
        $this->player = $player;
    }

    public function onRun(): void {
        $this->runs++;

        if($this->runs > 3) {
            if(in_array($this->player->getUniqueId()->toString(), DeepWounds::$bleeding)) unset(DeepWounds::$bleeding[array_search($this->player->getUniqueId()->toString(), DeepWounds::$bleeding)]);
            $this->getHandler()->cancel();
            return;
        }

        $ev = new  BleedDamageEvent($this->player, 0.5);
        $ev->call();

        $this->player->setHealth($this->player->getHealth() - 0.5);
        $this->player->getLocation()->getWorld()->addParticle($this->player->getPosition(), new RedstoneParticle());
    }
}