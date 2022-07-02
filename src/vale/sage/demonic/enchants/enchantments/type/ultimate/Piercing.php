<?php

declare(strict_types = 1);

namespace vale\sage\demonic\enchants\enchantments\type\ultimate;

use pocketmine\entity\effect\Effect;
use pocketmine\event\entity\ProjectileHitEntityEvent;
use pocketmine\item\enchantment\ItemFlags;
use pocketmine\player\Player;
use vale\sage\demonic\enchants\CustomEnchant;
use vale\sage\demonic\enchants\CustomEnchantIds;

class Piercing extends CustomEnchant {

    public function __construct() {
        parent::__construct(
            "Piercing",
            CustomEnchantIds::PIERCING,
            "Inflicts more damage.",
            5,
            ItemFlags::BOW,
            self::ULTIMATE,
            self::OFFENSIVE,
            self::PROJECTILE_ENTITY,
            self::BOW_2
        );

        $this->callable = function (ProjectileHitEntityEvent $event, int $level) : void {
            $entity = $event->getEntityHit();

            if(!$entity instanceof Player) return;

            $entity->setHealth($entity->getHealth() - ($level * 0.2));
        };
    }

}