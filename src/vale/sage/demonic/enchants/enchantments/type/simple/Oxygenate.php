<?php

declare(strict_types = 1);

namespace vale\sage\demonic\enchants\enchantments\type\simple;

use pocketmine\event\block\BlockBreakEvent;
use pocketmine\item\enchantment\ItemFlags;
use vale\sage\demonic\enchants\CustomEnchant;
use vale\sage\demonic\enchants\CustomEnchantIds;

class Oxygenate extends CustomEnchant {

    public function __construct() {
        parent::__construct(
            "Oxygenate",
            CustomEnchantIds::OXYGENATE,
            "Refills oxygen levels when breaking blocks under water.",
            1,
            ItemFlags::TOOL,
            self::SIMPLE,
            self::MINING,
            self::BREAK,
            self::TOOL
        );

        $this->callable = function (BlockBreakEvent $event) : void {
            if($event->getPlayer()->isUnderwater()) {
                $event->getPlayer()->setAirSupplyTicks($event->getPlayer()->getMaxAirSupplyTicks());
            }
        };
    }

}