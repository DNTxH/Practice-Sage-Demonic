<?php

declare(strict_types = 1);

namespace vale\sage\demonic\enchants\enchantments\type\legendary;

use pocketmine\entity\effect\Effect;
use pocketmine\event\entity\EntityEffectAddEvent;
use pocketmine\item\enchantment\ItemFlags;
use vale\sage\demonic\enchants\CustomEnchant;
use vale\sage\demonic\enchants\CustomEnchantIds;

class Overload extends CustomEnchant {

    public function __construct() {
        parent::__construct(
            "Overload",
            CustomEnchantIds::OVERLOAD,
            "Permanent increase in hearts.",
            3,
            ItemFlags::ARMOR,
            self::LEGENDARY,
            self::DEFENSIVE,
            self::EFFECT,
            self::ARMOR
        );

        $this->callable = function (EntityEffectAddEvent $event, int $level) : void {
            //
        };
    }
}