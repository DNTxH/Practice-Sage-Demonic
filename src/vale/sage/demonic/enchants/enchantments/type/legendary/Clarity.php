<?php

declare(strict_types = 1);

namespace vale\sage\demonic\enchants\enchantments\type\legendary;

use pocketmine\event\entity\EntityEffectAddEvent;
use pocketmine\item\enchantment\ItemFlags;
use vale\sage\demonic\enchants\CustomEnchant;
use vale\sage\demonic\enchants\CustomEnchantIds;

class Clarity extends CustomEnchant {

    public function __construct() {
        parent::__construct(
            "Clarity",
            CustomEnchantIds::CLARITY,
            "Immune to Blindness up to level of clarity enchantment.",
            3,
            ItemFlags::ARMOR,
            self::LEGENDARY,
            self::DEFENSIVE,
            self::EFFECT,
            self::ARMOR
        );

        $this->callable = function (EntityEffectAddEvent $event, int $level) : void {
            if($event->getEffect()->getType()->getName() === "Blindness") {
                if($level > $event->getEffect()->getEffectLevel()) $event->cancel();
            }
        };
    }
}