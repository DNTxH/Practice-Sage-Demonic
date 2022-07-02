<?php

declare(strict_types = 1);

namespace vale\sage\demonic\enchants\enchantments\type\ultimate;

use pocketmine\entity\effect\Effect;
use pocketmine\event\entity\EntityEffectAddEvent;
use pocketmine\item\enchantment\ItemFlags;
use vale\sage\demonic\enchants\CustomEnchant;
use vale\sage\demonic\enchants\CustomEnchantIds;

class ObsidianShield extends CustomEnchant {

    public function __construct() {
        parent::__construct(
            "Obsidian Shield",
            CustomEnchantIds::OBSIDIANSHIELD,
            "Gives permanent fire resistance.",
            1,
            ItemFlags::ARMOR,
            self::ULTIMATE,
            self::EFFECT,
            self::EFFECT,
            self::ARMOR
        );

        $this->callable = function (EntityEffectAddEvent $event, int $level) : void {

        };
    }

}