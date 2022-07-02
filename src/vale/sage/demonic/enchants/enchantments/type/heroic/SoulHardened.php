<?php

declare(strict_types = 1);

namespace vale\sage\demonic\enchants\enchantments\type\heroic;

use pocketmine\entity\effect\Effect;
use pocketmine\item\enchantment\ItemFlags;
use vale\sage\demonic\enchants\CustomEnchant;
use vale\sage\demonic\enchants\CustomEnchantIds;
use vale\sage\demonic\enchants\event\SoulTrapEvent;

class SoulHardened extends CustomEnchant {

    public function __construct() {
        parent::__construct(
            "Soul Hardened",
            CustomEnchantIds::SOULHARDENED,
            "Up to 50% chance to block enemy soul trap, armor takes less durability damage.",
            3,
            ItemFlags::ARMOR,
            self::HEROIC,
            self::DEFENSIVE,
            self::SOULTRAP,
            self::ARMOR
        );

        $this->callable = function (SoulTrapEvent $event, int $level) : void {
            if(mt_rand(0, 100) <= $level * 3) {
                $event->cancel();
            }
        };
    }
}