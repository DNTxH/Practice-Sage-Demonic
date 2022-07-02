<?php

declare(strict_types = 1);

namespace vale\sage\demonic\enchants\enchantments\type\ultimate;

use pocketmine\entity\effect\Effect;
use pocketmine\event\entity\EntityDamageByEntityEvent;
use pocketmine\item\enchantment\ItemFlags;
use pocketmine\player\Player;
use vale\sage\demonic\enchants\CustomEnchant;
use vale\sage\demonic\enchants\CustomEnchantIds;

class Tank extends CustomEnchant {

    public function __construct() {
        parent::__construct(
            "Tank",
            CustomEnchantIds::TANK,
            "Decreases damage from enemy axes by 1.85% per level, this enchantment is stackable.",
            4,
            ItemFlags::ARMOR,
            self::ULTIMATE,
            self::DEFENSIVE,
            self::ENTITY_DAMAGE_BY_ENTITY,
            self::ARMOR
        );

        $this->callable = function (EntityDamageByEntityEvent $event, int $level) : void {
            $reduce = 1 - (0.0185 * $level);
            $entity = $event->getEntity();
            $damager = $event->getDamager();

            if($event->isCancelled() || !$entity instanceof Player || !$damager instanceof Player) return;

            $event->setBaseDamage($event->getBaseDamage() * $reduce);
        };
    }
}