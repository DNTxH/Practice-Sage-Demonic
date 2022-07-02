<?php

declare(strict_types = 1);

namespace vale\sage\demonic\enchants\enchantments\type\simple;

use pocketmine\entity\effect\VanillaEffects;
use pocketmine\event\entity\EntityEffectAddEvent;
use pocketmine\item\enchantment\ItemFlags;
use vale\sage\demonic\enchants\CustomEnchant;
use vale\sage\demonic\enchants\CustomEnchantIds;

class Glowing extends CustomEnchant {

    public function __construct() {
        parent::__construct(
            "Glowing",
            CustomEnchantIds::GLOWING,
            "Gives permanent night vision.",
            1,
            ItemFlags::HEAD,
            self::SIMPLE,
            self::EFFECT,
            self::EFFECT,
            self::HELMET,
            VanillaEffects::NIGHT_VISION(),
            1
        );

        $this->callable = function (EntityEffectAddEvent $event) : void {

        };
    }

}