<?php

declare(strict_types = 1);

namespace vale\sage\demonic\enchants\enchantments\type\unique;

use pocketmine\entity\effect\Effect;
use pocketmine\entity\effect\EffectInstance;
use pocketmine\entity\effect\VanillaEffects;
use pocketmine\event\entity\EntityDamageByEntityEvent;
use pocketmine\item\enchantment\ItemFlags;
use pocketmine\player\Player;
use vale\sage\demonic\enchants\CustomEnchant;
use vale\sage\demonic\enchants\CustomEnchantIds;

class Commander extends CustomEnchant {

    public function __construct() {
        parent::__construct(
            "Commander",
            CustomEnchantIds::COMMANDER,
            "Chance to give nearby allies haste when hit.",
            5,
            ItemFlags::ARMOR,
            self::UNIQUE,
            self::DEFENSIVE,
            self::ENTITY_DAMAGE_BY_ENTITY,
            self::ARMOR
        );

        $this->callable = function (EntityDamageByEntityEvent $event, int $level) : void {
            $entity = $event->getEntity();
            $damager = $event->getDamager();

            if($event->isCancelled() || !$entity instanceof Player || !$damager instanceof Player) return;

            if(mt_rand(0, 100) <= $level * 2.5) {
                foreach ($entity->getWorld()->getNearbyEntities($entity->getBoundingBox()->expandedCopy($level * 3, $level * 3, $level * 3)) as $e) {
                    if(!$e instanceof Player) continue;
                    if(!$e->getEffects()->has(VanillaEffects::HASTE())) $e->getEffects()->add(new EffectInstance(VanillaEffects::HASTE(), 40, $level));
                }
            }
        };
    }

}