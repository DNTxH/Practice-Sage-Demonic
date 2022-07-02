<?php

namespace vale\sage\demonic\Partner\Task\ComboAbility;

use pocketmine\entity\effect\EffectInstance;
use pocketmine\entity\effect\VanillaEffects;
use pocketmine\player\Player;
use pocketmine\scheduler\Task;
use vale\sage\demonic\Loader;

class EndTask extends Task
{
    private Player $player;

    public function __construct(Player $player)
    {
        $this->player = $player;
    }

    public function onRun(): void
    {
        $hit_count = Loader::$comboAbility["hit"][$this->player->getName()];
        if($hit_count === 0){
            $this->player->sendMessage("§a[Combo Ability]§c You didn't hit anyone!");
        } else {
            $this->player->getEffects()->add(new EffectInstance(VanillaEffects::STRENGTH(),20 * $hit_count,1));
            $this->player->sendMessage("§a[Combo Ability]§c You have been given $hit_count seconds Strength II!");
        }
        Loader::$comboAbility["hit"][$this->player->getName()] = 0;
    }

}