<?php

namespace vale\sage\demonic\enchants\enchantments\type\ultimate;

use pocketmine\entity\effect\Effect;
use pocketmine\item\enchantment\ItemFlags;
use vale\sage\demonic\enchants\CustomEnchant;
use vale\sage\demonic\enchants\CustomEnchantIds;
use vale\sage\demonic\enchants\event\PlayerDisarmorEvent;

class Sticky extends CustomEnchant {

    public function __construct() {
        parent::__construct(
            "Sticky",
            CustomEnchantIds::STICKY,
            "Decreases the chance of an enemy's Disarmor enchantment procing on you by 12.5% per level. At max level, you can never be disarmored.",
            8,
            ItemFlags::ARMOR,
            self::LEGENDARY,
            self::DEFENSIVE,
            self::DISARMOR,
            self::ARMOR
        );

        $this->callable = function (PlayerDisarmorEvent $event, int $level) : void {
            if(mt_rand(0, 100) > ((100 / 8) * $level)) $event->cancel();
        };
    }


}