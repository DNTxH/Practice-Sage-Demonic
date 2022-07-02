<?php

declare(strict_types = 1);

namespace vale\sage\demonic\enchants\enchantments\type\elite;

use pocketmine\entity\effect\Effect;
use pocketmine\entity\effect\VanillaEffects;
use pocketmine\event\entity\EntityEffectAddEvent;
use pocketmine\item\enchantment\ItemFlags;
use vale\sage\demonic\enchants\CustomEnchant;
use vale\sage\demonic\enchants\CustomEnchantIds;

class Springs extends CustomEnchant {

    public function __construct() {
        parent::__construct(
            "Springs",
            CustomEnchantIds::SPRINGS,
            "Gives jump boost.",
            3,
            ItemFlags::FEET,
            self::ELITE,
            self::DEFENSIVE,
            self::EFFECT,
            self::BOOTS,
            VanillaEffects::JUMP_BOOST(),
            1
        );

        $this->callable = function (EntityEffectAddEvent $event, int $level) : void {

        };
    }

}