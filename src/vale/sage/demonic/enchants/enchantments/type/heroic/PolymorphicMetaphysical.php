<?php

declare(strict_types = 1);

namespace vale\sage\demonic\enchants\enchantments\type\heroic;

use pocketmine\entity\effect\Effect;
use pocketmine\item\enchantment\ItemFlags;
use vale\sage\demonic\enchants\CustomEnchantIds;
use vale\sage\demonic\enchants\event\MetaphysicalEvent;

class PolymorphicMetaphysical extends HeroicCustomEnchant {

    public function __construct() {
        parent::__construct(
            "Polymorphic Metaphysical",
            CustomEnchantIds::POLYMORPHICMETAPHYSICAL,
            "A chance to resist the slowness given by enemy Trap, Snare, Pummel, and Ice Aspect enchantments. At max level, you will only be affected approx. 10% of the time.",
            4,
            ItemFlags::FEET,
            self::HEROIC,
            self::DEFENSIVE,
            self::METAPHYSICAL,
            self::BOOTS,
            CustomEnchantIds::POLYMORPHICMETAPHYSICAL
        );

        $this->callable = function (MetaphysicalEvent $event, int $level) : void {
            if(mt_rand(0, 100) <= 22.5 * $level) {
                $event->cancel();
            }
        };
    }
}