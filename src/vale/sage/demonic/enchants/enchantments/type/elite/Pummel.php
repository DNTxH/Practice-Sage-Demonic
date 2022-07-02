<?php

declare(strict_types = 1);

namespace vale\sage\demonic\enchants\enchantments\type\elite;

use pocketmine\entity\effect\Effect;
use pocketmine\entity\effect\EffectInstance;
use pocketmine\entity\effect\VanillaEffects;
use pocketmine\event\entity\EntityDamageByEntityEvent;
use pocketmine\item\enchantment\ItemFlags;
use pocketmine\player\Player;
use vale\sage\demonic\enchants\CustomEnchant;
use vale\sage\demonic\enchants\CustomEnchantIds;
use vale\sage\demonic\enchants\event\MetaphysicalEvent;

class Pummel extends CustomEnchant {

    public function __construct() {
        parent::__construct(
            "Pummel",
            CustomEnchantIds::PUMMEL,
            "Chance to slow nearby enemy players for a short period.",
            3,
            ItemFlags::AXE,
            self::ELITE,
            self::OFFENSIVE,
            self::ENTITY_DAMAGE_BY_ENTITY,
            self::AXE
        );

        $this->callable = function (EntityDamageByEntityEvent $event, int $level) : void {
            $entity = $event->getEntity();
            $damager = $event->getDamager();

            if($event->isCancelled() || !$entity instanceof Player || !$damager instanceof Player) return;

            if(mt_rand(0, 100) <= $level * 3) {
                foreach ($entity->getWorld()->getNearbyEntities($entity->getBoundingBox()->expandedCopy($level * 2, $level * 2, $level * 2)) as $e) {
                    if(!$e instanceof Player) continue;

                    $ev = new MetaphysicalEvent($e);

                    $ev->call();

                    if($ev->isCancelled()) return;

                    if(!$e->getEffects()->has(VanillaEffects::SLOWNESS())) $e->getEffects()->add(new EffectInstance(VanillaEffects::SLOWNESS(), 40, $level));
                }
            }
        };
    }

}