<?php

declare(strict_types = 1);

namespace vale\sage\demonic\enchants\enchantments\type\legendary;

use pocketmine\entity\effect\Effect;
use pocketmine\entity\effect\VanillaEffects;
use pocketmine\event\entity\EntityEffectAddEvent;
use pocketmine\item\enchantment\ItemFlags;
use vale\sage\demonic\enchants\CustomEnchant;
use vale\sage\demonic\enchants\CustomEnchantIds;

class Gears extends CustomEnchant {

    public function __construct() {
        parent::__construct(
            "Gears",
            CustomEnchantIds::GEARS,
            "Added speed when equipped.",
            3,
            ItemFlags::FEET,
            self::LEGENDARY,
            self::DEFENSIVE,
            self::EFFECT,
            self::BOOTS,
            VanillaEffects::SPEED(),
            1
        );

        $this->callable = function (EntityEffectAddEvent $event, int $level) : void {

        };
    }

}