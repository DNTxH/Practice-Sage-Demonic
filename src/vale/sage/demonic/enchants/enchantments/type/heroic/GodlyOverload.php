<?php

declare(strict_types = 1);

namespace vale\sage\demonic\enchants\enchantments\type\heroic;


use pocketmine\entity\effect\Effect;
use pocketmine\event\entity\EntityEffectAddEvent;
use pocketmine\item\enchantment\ItemFlags;
use vale\sage\demonic\enchants\CustomEnchantIds;

class GodlyOverload extends HeroicCustomEnchant {

    public function __construct() {
        parent::__construct(
            "Godly Overload",
            CustomEnchantIds::GODLYOVERLOAD,
            "A very large permanent increase in hearts. Requires Overload III enchant on item to apply.",
            3,
            ItemFlags::ARMOR,
            self::HEROIC,
            self::DEFENSIVE,
            self::EFFECT,
            self::ARMOR,
            CustomEnchantIds::OVERLOAD
        );

        $this->callable = function (EntityEffectAddEvent $event, int $level) : void {
            //
        };
    }
}