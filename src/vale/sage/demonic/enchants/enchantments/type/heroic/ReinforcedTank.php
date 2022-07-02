<?php

declare(strict_types = 1);

namespace vale\sage\demonic\enchants\enchantments\type\heroic;

use pocketmine\entity\effect\Effect;
use pocketmine\event\entity\EntityDamageByEntityEvent;
use pocketmine\item\enchantment\ItemFlags;
use pocketmine\player\Player;
use vale\sage\demonic\enchants\CustomEnchantIds;

class ReinforcedTank extends HeroicCustomEnchant {

    public function __construct() {
        parent::__construct(
            "Reinforced Tank",
            CustomEnchantIds::REINFORCEDTANK,
            "Decreases Damage from enemy axes by 2.25% per level, this enchantment is stackable.",
            4,
            ItemFlags::ARMOR,
            self::HEROIC,
            self::DEFENSIVE,
            self::ENTITY_DAMAGE_BY_ENTITY,
            self::ARMOR,
            CustomEnchantIds::TANK
        );

        $this->callable = function (EntityDamageByEntityEvent $event, int $level) : void {
            $reduce = 1 - (0.0225 * $level);
            $entity = $event->getEntity();
            $damager = $event->getDamager();

            if($event->isCancelled() || !$entity instanceof Player || !$damager instanceof Player) return;

            $event->setBaseDamage($event->getBaseDamage() * $reduce);
        };
    }
}