<?php

declare(strict_types = 1);

namespace vale\sage\demonic\enchants\enchantments\type\unique;

use pocketmine\entity\effect\EffectInstance;
use pocketmine\entity\effect\VanillaEffects;
use pocketmine\event\entity\EntityDamageByEntityEvent;
use pocketmine\item\enchantment\ItemFlags;
use pocketmine\player\Player;
use vale\sage\demonic\enchants\CustomEnchant;
use vale\sage\demonic\enchants\CustomEnchantIds;

class EnderShift extends CustomEnchant {

    public function __construct() {
        parent::__construct(
            "Ender Shift",
            CustomEnchantIds::ENDERSHIFT,
            "Gives a speed/health boost at low hp.",
            3,
            ItemFlags::HEAD | ItemFlags::FEET,
            self::UNIQUE,
            self::DEFENSIVE,
            self::ENTITY_DAMAGE_BY_ENTITY,
            self::BOOTS | self::HELMET
        );

        $this->callable = function (EntityDamageByEntityEvent $event, int $level) : void {
            $entity = $event->getEntity();
            $damager = $event->getDamager();

            if($event->isCancelled() || !$entity instanceof Player || !$damager instanceof Player) return;

            if(mt_rand(0, 100) <= $level * 5) {
                if($entity->getHealth() <= 8.0) {
                    if(!$entity->getEffects()->has(VanillaEffects::SPEED())) $entity->getEffects()->add(new EffectInstance(VanillaEffects::SPEED(), $level * 20, $level, false));
                    if(!$entity->getEffects()->has(VanillaEffects::HEALTH_BOOST())) $entity->getEffects()->add(new EffectInstance(VanillaEffects::HEALTH_BOOST(), $level * 20, $level, false));
                }
            }
        };
    }

}