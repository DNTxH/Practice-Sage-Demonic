<?php

declare(strict_types = 1);

namespace vale\sage\demonic\enchants\enchantments\type\unique;

use pocketmine\event\entity\EntityDamageByEntityEvent;
use pocketmine\item\enchantment\ItemFlags;
use pocketmine\player\Player;
use vale\sage\demonic\enchants\CustomEnchant;
use vale\sage\demonic\enchants\CustomEnchantIds;

class Ravenous extends CustomEnchant {

    public function __construct() {
        parent::__construct(
            "Ravenous",
            CustomEnchantIds::RAVENOUS,
            "A chance to regain hunger.",
            4,
            ItemFlags::AXE,
            self::UNIQUE,
            self::OFFENSIVE,
            self::ENTITY_DAMAGE_BY_ENTITY,
            self::AXE
        );

        $this->callable = function (EntityDamageByEntityEvent $event, int $level) : void {
            $damager = $event->getDamager();

            if(!$damager instanceof Player || $event->isCancelled()) return;

            if(mt_rand(0, 100) <= $level * 2) {
                if($damager->getHungerManager()->getFood() + 0.5 <= $damager->getHungerManager()->getMaxFood()) $damager->getHungerManager()->setFood($damager->getHungerManager()->getFood() + 0.5);
            }
        };
    }


}