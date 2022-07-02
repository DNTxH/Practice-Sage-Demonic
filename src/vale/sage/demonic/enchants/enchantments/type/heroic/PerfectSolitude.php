<?php

declare(strict_types = 1);

namespace vale\sage\demonic\enchants\enchantments\type\heroic;

use pocketmine\entity\effect\Effect;
use pocketmine\event\entity\EntityDamageByEntityEvent;
use pocketmine\item\enchantment\ItemFlags;
use vale\sage\demonic\enchants\CustomEnchant;
use vale\sage\demonic\enchants\CustomEnchantIds;

class PerfectSolitude extends CustomEnchant {

    public function __construct() {
        parent::__construct(
            "Perfect Solitude",
            CustomEnchantIds::PERFECTSOLITUDE,
            "ncreases chance and length of the Silence enchantment procing on enemy players by up to 6X",
            3,
            ItemFlags::SWORD | ItemFlags::AXE,
            self::HEROIC,
            self::OFFENSIVE,
            self::ENTITY_DAMAGE_BY_ENTITY,
            self::SWORD | self::AXE
        );

        $this->callable = function (EntityDamageByEntityEvent $event, int $level) : void {

        };
    }
}