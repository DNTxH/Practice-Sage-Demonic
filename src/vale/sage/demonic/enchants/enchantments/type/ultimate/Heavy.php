<?php

declare(strict_types = 1);

namespace vale\sage\demonic\enchants\enchantments\type\ultimate;

use pocketmine\entity\effect\Effect;
use pocketmine\event\entity\EntityDamageByEntityEvent;
use pocketmine\item\enchantment\ItemFlags;
use pocketmine\player\Player;
use vale\sage\demonic\enchants\CustomEnchant;
use vale\sage\demonic\enchants\CustomEnchantIds;

class Heavy extends CustomEnchant {

    public function __construct() {
        parent::__construct(
            "Heavy",
            CustomEnchantIds::HEAVY,
            "Decreases damage from enemy bows by 2% per level, this enchantment is stackable.",
            5,
            ItemFlags::ARMOR,
            self::ULTIMATE,
            self::DEFENSIVE,
            self::ENTITY_DAMAGE_BY_ENTITY,
            self::ARMOR
        );

        $this->callable = function (EntityDamageByEntityEvent $event, int $level) : void {
            $entity = $event->getEntity();
            $damager = $event->getDamager();
            $multi = 1 - ($level * 0.02);

            if($event->isCancelled() || !$entity instanceof Player || !$damager instanceof Player) return;

            if($event->getCause() === EntityDamageByEntityEvent::CAUSE_PROJECTILE) {
                $event->setBaseDamage($event->getBaseDamage() * $multi);
            }
        };
    }

}