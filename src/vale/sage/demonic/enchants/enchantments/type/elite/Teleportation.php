<?php

declare(strict_types = 1);

namespace vale\sage\demonic\enchants\enchantments\type\elite;

use pocketmine\entity\effect\Effect;
use pocketmine\event\entity\ProjectileHitEntityEvent;
use pocketmine\item\enchantment\ItemFlags;
use pocketmine\player\Player;
use vale\sage\demonic\enchants\CustomEnchant;
use vale\sage\demonic\enchants\CustomEnchantIds;

class Teleportation extends CustomEnchant {

    public function __construct() {
        parent::__construct(
            "Teleportation",
            CustomEnchantIds::TELEPORTATION,
            "When an ally or faction member is hit you teleport to them.",
            5,
            ItemFlags::BOW,
            self::UNIQUE,
            self::DEFENSIVE,
            self::PROJECTILE_ENTITY,
            self::BOW_2
        );

        $this->callable = function (ProjectileHitEntityEvent $event, int $level) : void {
            $entity = $event->getEntityHit();
            $owner = $event->getEntity()->getOwningEntity();

            if(!$entity instanceof Player || !$owner instanceof Player) return;

            // todo check if player hit is fac member
            if(mt_rand(0, 100) <= $level * 4) {
                $owner->teleport($entity->getPosition());
            }
        };
    }

}