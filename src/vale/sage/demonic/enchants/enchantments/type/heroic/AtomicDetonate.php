<?php

declare(strict_types = 1);

namespace vale\sage\demonic\enchants\enchantments\type\heroic;

use pocketmine\event\block\BlockBreakEvent;
use pocketmine\item\enchantment\ItemFlags;
use pocketmine\world\particle\BlockBreakParticle;
use vale\sage\demonic\enchants\CustomEnchantIds;
use vale\sage\demonic\enchants\utils\DetonateExplosion;

class AtomicDetonate extends HeroicCustomEnchant {

    public function __construct() {
        parent::__construct(
            "Atomic Detonate",
            CustomEnchantIds::ATOMICDETONATE,
            "Summons up to a 7x7x7 explosion around any block you break.",
            4,
            ItemFlags::PICKAXE,
            self::HEROIC,
            self::MINING,
            self::BREAK,
            self::PICKAXE,
            CustomEnchantIds::DETONATE
        );

        $this->callable = function (BlockBreakEvent $event, int $level) : void {
            $explosion = new DetonateExplosion($event->getBlock()->getPosition(), $level + 3, $event->getPlayer());
            $explosion->explodeA();
            $explosion->explodeB();
        };
    }

}