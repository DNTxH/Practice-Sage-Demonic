<?php

namespace vale\sage\demonic\enchants\enchantments\type\ultimate;

use pocketmine\entity\effect\Effect;
use pocketmine\event\entity\ProjectileHitEntityEvent;
use pocketmine\item\enchantment\ItemFlags;
use pocketmine\player\Player;
use vale\sage\demonic\enchants\CustomEnchant;
use vale\sage\demonic\enchants\CustomEnchantIds;

class ArrowLifesteal extends CustomEnchant {

    public function __construct() {
        parent::__construct(
            "Arrow Lifesteal",
            CustomEnchantIds::ARROWLIFESTEAL,
            "A chance to steal health from opponent while fighting.",
            5,
            ItemFlags::BOW,
            self::ULTIMATE,
            self::OFFENSIVE,
            self::PROJECTILE_ENTITY,
            self::BOW_2
        );

        $this->callable = function (ProjectileHitEntityEvent $event, int $level) : void {
            $entity = $event->getEntityHit();
            $owner = $event->getEntity()->getOwningEntity();

            if(!$entity instanceof Player || !$owner instanceof Player) return;

            $health = $level * 0.25;

            if(mt_rand(0, 100) <= $level * 2) {
                $entity->setHealth($entity->getHealth() - $health);
                $owner->setHealth($entity->getHealth() + $health);
            }
        };
    }

}