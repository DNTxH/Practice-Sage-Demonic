<?php

declare(strict_types = 1);

namespace vale\sage\demonic\enchants\enchantments\type\elite;

use pocketmine\entity\effect\Effect;
use pocketmine\event\entity\ProjectileHitEntityEvent;
use pocketmine\item\enchantment\ItemFlags;
use pocketmine\world\Explosion;
use pocketmine\world\particle\FlameParticle;
use vale\sage\demonic\enchants\CustomEnchant;
use vale\sage\demonic\enchants\CustomEnchantIds;

class Infernal extends CustomEnchant {

    public function __construct() {
        parent::__construct(
            "Infernal",
            CustomEnchantIds::INFERNAL,
            "Explosive fire effect.",
            3,
            ItemFlags::BOW,
            self::ELITE,
            self::OFFENSIVE,
            self::PROJECTILE_ENTITY,
            self::BOW_2
        );

        $this->callable = function (ProjectileHitEntityEvent $event, int $level) : void {
            $entity = $event->getEntityHit();

            $explosion = new Explosion($entity->getPosition(), $level, $entity);
            $explosion->explodeB();

            $entity->setOnFire($level);
            $entity->getWorld()->addParticle($entity->getPosition(), new FlameParticle());
        };
    }

}