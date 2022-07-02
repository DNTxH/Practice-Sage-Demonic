<?php

namespace vale\sage\demonic\enchants\projectile;

use pocketmine\entity\projectile\Projectile;
use pocketmine\event\entity\ProjectileHitEvent;

abstract class NiggaProjectile extends Projectile
{
    public function onHit(ProjectileHitEvent $event) : void {
        $this->flagForDespawn();
        parent::onHit($event);
    }

    public function canSaveWithChunk() : bool {
        return false;
    }
}