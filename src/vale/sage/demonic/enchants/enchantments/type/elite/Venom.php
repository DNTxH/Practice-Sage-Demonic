<?php

declare(strict_types = 1);

namespace vale\sage\demonic\enchants\enchantments\type\elite;

use pocketmine\entity\effect\Effect;
use pocketmine\entity\effect\EffectInstance;
use pocketmine\entity\effect\VanillaEffects;
use pocketmine\event\entity\ProjectileHitEntityEvent;
use pocketmine\item\enchantment\ItemFlags;
use pocketmine\player\Player;
use vale\sage\demonic\enchants\CustomEnchant;
use vale\sage\demonic\enchants\CustomEnchantIds;

class Venom extends CustomEnchant {

    public function __construct() {
        parent::__construct(
            "Venom",
            CustomEnchantIds::VENOM,
            "A chance of dealing poison.",
            3,
            ItemFlags::BOW,
            self::ELITE,
            self::OFFENSIVE,
            self::PROJECTILE_ENTITY,
            self::BOW_2
        );

        $this->callable = function (ProjectileHitEntityEvent $event, int $level) : void {
            $entity = $event->getEntityHit();

            if($entity instanceof Player && mt_rand(0, 100) <= $level * 2.5) {
                if(!$entity->getEffects()->has(VanillaEffects::POISON())) $entity->getEffects()->add(new EffectInstance(VanillaEffects::POISON(), $level * 30, 2));
            }
        };
    }

}