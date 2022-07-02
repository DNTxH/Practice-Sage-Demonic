<?php

declare(strict_types = 1);

namespace vale\sage\demonic\enchants\enchantments\type\ultimate;

use pocketmine\entity\effect\Effect;
use pocketmine\item\enchantment\ItemFlags;
use vale\sage\demonic\enchants\CustomEnchant;
use vale\sage\demonic\enchants\CustomEnchantIds;
use vale\sage\demonic\enchants\event\MetaphysicalEvent;

class Metaphysical extends CustomEnchant {

    public function __construct() {
        parent::__construct(
            "Metaphysical",
            CustomEnchantIds::METAPHYSICAL,
            "A chance to resist the slowness given by enemy Trap, Snare, and Pummel enchantments. At max level, you will only be affected approx. 10% of the time.",
            4,
            ItemFlags::FEET,
            self::ULTIMATE,
            self::DEFENSIVE,
            self::METAPHYSICAL,
            self::BOOTS
        );

        $this->callable = function (MetaphysicalEvent $event, int $level) : void {
            if(mt_rand(0, 100) <= 22.5 * $level) {
                $event->cancel();
            }
        };
    }

}