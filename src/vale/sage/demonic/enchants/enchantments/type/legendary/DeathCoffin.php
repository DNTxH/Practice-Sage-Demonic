<?php

declare(strict_types = 1);

namespace vale\sage\demonic\enchants\enchantments\type\legendary;

use pocketmine\entity\effect\Effect;
use pocketmine\event\entity\EntityDamageByEntityEvent;
use pocketmine\item\enchantment\ItemFlags;
use pocketmine\player\Player;
use vale\sage\demonic\enchants\CustomEnchant;
use vale\sage\demonic\enchants\CustomEnchantIds;

class DeathCoffin extends CustomEnchant {

    public function __construct() {
        parent::__construct(
            "Death Coffin",
            CustomEnchantIds::DEATHCOFFIN,
            "Any damage dealt to enemy players under 33% HP applies to all enemies within (level) blocks of target",
            2,
            ItemFlags::SWORD | ItemFlags::AXE,
            self::LEGENDARY,
            self::OFFENSIVE,
            self::ENTITY_DAMAGE_BY_ENTITY,
            self::SWORD | self::AXE
        );

        $this->callable = function (EntityDamageByEntityEvent $event, int $level) : void {
            $entity = $event->getEntity();
            $damager = $event->getDamager();

            if($event->isCancelled() || !$entity instanceof Player || !$damager instanceof Player) return;

            if($entity->getHealth() <= ($entity->getMaxHealth() / 3)) {
                foreach($entity->getWorld()->getNearbyEntities($entity->getBoundingBox()->expandedCopy($level, $level, $level)) as $e) {
                    if(!$e instanceof Player) continue;
                    $e->setHealth($e->getHealth() - $event->getFinalDamage());
                }
            }
        };
    }

}