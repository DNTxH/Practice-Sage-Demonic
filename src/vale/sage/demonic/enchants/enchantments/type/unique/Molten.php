<?php

declare(strict_types = 1);

namespace vale\sage\demonic\enchants\enchantments\type\unique;

use pocketmine\entity\effect\Effect;
use pocketmine\event\entity\EntityDamageByEntityEvent;
use pocketmine\item\enchantment\ItemFlags;
use pocketmine\player\Player;
use vale\sage\demonic\enchants\CustomEnchant;
use vale\sage\demonic\enchants\CustomEnchantIds;

class Molten extends CustomEnchant {

    public function __construct() {
        parent::__construct(
            "Molten",
            CustomEnchantIds::MOLTEN,
            "Chance of setting your attacker ablaze",
            4,
            ItemFlags::ARMOR,
            self::UNIQUE,
            self::DEFENSIVE,
            self::ENTITY_DAMAGE_BY_ENTITY,
            self::ARMOR
        );

        $this->callable = function (EntityDamageByEntityEvent $event, int $level) : void {
            $entity = $event->getEntity();
            $damager = $event->getDamager();

            if(!$entity instanceof Player || !$damager instanceof Player || $event->isCancelled()) return;

            if(mt_rand(0, 100) < $level * 1.5) {
                if(!$damager->isOnFire()) $damager->setOnFire($level);
            }
        };
    }


}