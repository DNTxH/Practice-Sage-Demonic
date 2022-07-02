<?php

declare(strict_types = 1);

namespace vale\sage\demonic\enchants\enchantments\type\unique;

use pocketmine\event\entity\ProjectileHitEntityEvent;
use pocketmine\item\enchantment\ItemFlags;
use pocketmine\world\Explosion;
use vale\sage\demonic\enchants\CustomEnchant;
use vale\sage\demonic\enchants\CustomEnchantIds;

class Explosive extends CustomEnchant {

    public function __construct() {
        parent::__construct(
        "Explosive",
        CustomEnchantIds::EXPLOSIVE,
        "Explosive arrows.",
        5,
        ItemFlags::BOW,
        self::UNIQUE,
        self::BOW,
        self::PROJECTILE_ENTITY,
        self::BOW_2
        );

        $this->callable = function (ProjectileHitEntityEvent $event, int $level) : void {
            $entity = $event->getEntityHit();

            $explosion = new Explosion($entity->getPosition(), $level, $entity);
            $explosion->explodeB();
        };
    }

}