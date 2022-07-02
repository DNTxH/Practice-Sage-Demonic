<?php

declare(strict_types = 1);

namespace vale\sage\demonic\enchants\enchantments\type\legendary;

use pocketmine\entity\effect\EffectInstance;
use pocketmine\entity\effect\VanillaEffects;
use pocketmine\event\entity\EntityDamageByEntityEvent;
use pocketmine\item\enchantment\ItemFlags;
use pocketmine\player\Player;
use vale\sage\demonic\enchants\CustomEnchant;
use vale\sage\demonic\enchants\CustomEnchantIds;

class Destruction extends CustomEnchant {

    public function __construct() {
        parent::__construct(
            "Destruction",
            CustomEnchantIds::DESTRUCTION,
            "Automatically damages and debuffs all nearby enemies.",
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

            if(mt_rand(0, 100) <= $level * 3) {
                foreach($entity->getWorld()->getNearbyEntities($entity->getBoundingBox()->expandedCopy($level, $level, $level)) as $e) {
                    if(!$e instanceof Player) continue;

                    $e->setHealth($e->getHealth() * (0.2 * $level));#

                    if(!$entity->getEffects()->has(VanillaEffects::POISON())) $entity->getEffects()->add(new EffectInstance(VanillaEffects::POISON(), $level * 20, 1));
                    if(!$entity->getEffects()->has(VanillaEffects::WITHER())) $entity->getEffects()->add(new EffectInstance(VanillaEffects::WITHER(), $level * 20, 1));
                    if(!$entity->getEffects()->has(VanillaEffects::WEAKNESS())) $entity->getEffects()->add(new EffectInstance(VanillaEffects::WEAKNESS(), $level * 20, 1));
                }
            }
        };
    }
}