<?php

declare(strict_types = 1);

namespace vale\sage\demonic\enchants\enchantments\type\simple;

use pocketmine\entity\effect\VanillaEffects;
use pocketmine\event\entity\EntityEffectAddEvent;
use pocketmine\item\enchantment\ItemFlags;
use vale\sage\demonic\enchants\CustomEnchant;
use vale\sage\demonic\enchants\CustomEnchantIds;

class Aquatic extends CustomEnchant {

    public function __construct()
    {
        parent::__construct(
            "Aquatic",
            CustomEnchantIds::AQUATIC,
            "Gives permanent water breathing.",
            1,
            ItemFlags::HEAD,
            self::SIMPLE,
            self::EFFECT,
            self::EFFECT,
            self::HELMET,
            VanillaEffects::WATER_BREATHING(),
            1
        );

        $this->callable = function (EntityEffectAddEvent $event): void {
            // keep it happy
        };
    }
}
