<?php

declare(strict_types = 1);

namespace vale\sage\demonic\enchants\enchantments\type\heroic;

use pocketmine\entity\effect\Effect;
use pocketmine\event\entity\EntityDamageByEntityEvent;
use pocketmine\item\enchantment\ItemFlags;
use pocketmine\player\Player;
use vale\sage\demonic\enchants\CustomEnchantIds;

class MightyCleave extends HeroicCustomEnchant {

    public function __construct() {
        parent::__construct(
            "Mighty Cleave",
            CustomEnchantIds::MIGHTYCLEAVE,
            "The heroic version of Cleave. Deals up to 8 damage in up to a 4.25 block radius.",
            5,
            ItemFlags::AXE,
            self::HEROIC,
            self::OFFENSIVE,
            self::ENTITY_DAMAGE_BY_ENTITY,
            self::AXE,
            CustomEnchantIds::CLEAVE
        );

        $this->callable = function (EntityDamageByEntityEvent $event, int $level) : void {
            $entity = $event->getEntity();
            $damager = $event->getDamager();

            if($event->isCancelled() || !$entity instanceof Player || !$damager instanceof Player) return;

            foreach ($damager->getWorld()->getNearbyEntities($damager->getBoundingBox()->expandedCopy(0.85 * $level, 0.85 * $level, 0.85 * $level)) as $e) {
                if(!$e instanceof Player) continue;
                $ev = new EntityDamageByEntityEvent($damager, $e, EntityDamageByEntityEvent::CAUSE_ENTITY_ATTACK, 1.6 * $level);
                $ev->call();
            }
        };
    }

}