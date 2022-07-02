<?php

declare(strict_types = 1);

namespace vale\sage\demonic\enchants\enchantments\type\legendary;

use pocketmine\entity\effect\Effect;
use pocketmine\entity\effect\EffectInstance;
use pocketmine\entity\effect\VanillaEffects;
use pocketmine\event\entity\EntityDamageByEntityEvent;
use pocketmine\item\enchantment\ItemFlags;
use pocketmine\player\Player;
use vale\sage\demonic\enchants\CustomEnchant;
use vale\sage\demonic\enchants\CustomEnchantIds;

class Protection extends CustomEnchant {

    public function __construct() {
        parent::__construct(
            "Protection",
            CustomEnchantIds::PROTECTION,
            "Automatically heals and buffs all nearby faction allies.",
            5,
            ItemFlags::ARMOR,
            self::LEGENDARY,
            self::DEFENSIVE,
            self::ENTITY_DAMAGE_BY_ENTITY,
            self::ARMOR
        );

        $this->callable = function (EntityDamageByEntityEvent $event, int $level) : void {
            $entity = $event->getEntity();
            $damager = $event->getDamager();

            if($event->isCancelled() || !$entity instanceof Player || !$damager instanceof Player) return;

            foreach($entity->getWorld()->getNearbyEntities($entity->getBoundingBox()->expandedCopy($level * 3, $level * 3, $level * 3)) as $e) {
                if(!$e instanceof Player) continue;
                $e->setHealth($e->getHealth() + 20.0);
                if(!$e->getEffects()->has(VanillaEffects::REGENERATION())) $e->getEffects()->add(new EffectInstance(VanillaEffects::REGENERATION(), 200, 5));
                if(!$e->getEffects()->has(VanillaEffects::RESISTANCE())) $e->getEffects()->add(new EffectInstance(VanillaEffects::RESISTANCE(), 200, 5));
            }
        };
    }

}