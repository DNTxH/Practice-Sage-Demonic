<?php

declare(strict_types = 1);

namespace vale\sage\demonic\enchants\enchantments\type\ultimate;

use pocketmine\event\block\BlockBreakEvent;
use pocketmine\item\enchantment\ItemFlags;
use vale\sage\demonic\enchants\CustomEnchant;
use vale\sage\demonic\enchants\CustomEnchantIds;
use vale\sage\demonic\enchants\utils\DetonateExplosion;

class Detonate extends CustomEnchant {

    public function __construct() {
        parent::__construct(
            "Detonate",
            CustomEnchantIds::DETONATE,
            "Summons up to a 3x3x3 explosion around any blocks you break",
            9,
            ItemFlags::PICKAXE,
            self::ULTIMATE,
            self::MINING,
            self::BREAK,
            self::PICKAXE
        );

        $this->callable = function (BlockBreakEvent $event, int $level) : void {
            $explosion = new DetonateExplosion($event->getBlock()->getPosition(), $level, $event->getPlayer());
            $explosion->explodeA();
            $explosion->explodeB();
        };
    }

}