<?php

declare(strict_types = 1);

namespace vale\sage\demonic\enchants\enchantments\type\ultimate;

use pocketmine\entity\effect\Effect;
use pocketmine\event\entity\EntityDamageByEntityEvent;
use pocketmine\item\enchantment\ItemFlags;
use pocketmine\player\Player;
use vale\sage\demonic\enchants\CustomEnchant;
use vale\sage\demonic\enchants\CustomEnchantIds;

class Cleave extends CustomEnchant {

    public function __construct() {
        parent::__construct(
            "Cleave",
            CustomEnchantIds::CLEAVE,
            "Damages players within a radius that increases with the level of enchant.",
            7,
            ItemFlags::AXE,
            self::ULTIMATE,
            self::OFFENSIVE,
            self::ENTITY_DAMAGE_BY_ENTITY,
            self::AXE
        );

        $this->callable = function (EntityDamageByEntityEvent $event, int $level) : void {
            $entity = $event->getEntity();
            $damager = $event->getDamager();

            if($event->isCancelled() || !$entity instanceof Player || !$damager instanceof Player) return;

            foreach ($damager->getWorld()->getNearbyEntities($damager->getBoundingBox()->expandedCopy($level * 0.5, $level * 0.5, $level * 0.5)) as $e) {
                if(!$e instanceof Player) continue;
                $ev = new EntityDamageByEntityEvent($damager, $e, EntityDamageByEntityEvent::CAUSE_ENTITY_ATTACK, $event->getBaseDamage());
                $ev->call();
            }
        };
    }

}