<?php

declare(strict_types = 1);

namespace vale\sage\demonic\enchants\enchantments\type\elite;

use pocketmine\entity\effect\Effect;
use pocketmine\event\entity\EntityDamageByEntityEvent;
use pocketmine\item\enchantment\ItemFlags;
use pocketmine\player\Player;
use vale\sage\demonic\enchants\CustomEnchant;
use vale\sage\demonic\enchants\CustomEnchantIds;

class SpiritLink extends CustomEnchant {

    public function __construct() {
        parent::__construct(
            "Spirit Link",
            CustomEnchantIds::SPIRITLINK,
            "Chance to heal nearby faction/allies when you are damaged.",
            5,
            ItemFlags::TORSO,
            self::ELITE,
            self::DEFENSIVE,
            self::ENTITY_DAMAGE_BY_ENTITY,
            self::CHESTPLATE
        );

        $this->callable = function (EntityDamageByEntityEvent $event, int $level) : void {
            $entity = $event->getEntity();
            $damager = $event->getDamager();

            if($event->isCancelled() || !$entity instanceof Player || !$damager instanceof Player) return;

            if(mt_rand(0, 100) <= $level * 3) {
                // todo check allies
                foreach($entity->getWorld()->getNearbyEntities($entity->getBoundingBox()->expandedCopy(3, 3, 3)) as $e) {
                    if(!$e instanceof Player) return;
                    $e->setHealth($e->getHealth() + 0.2);
                }
            }
        };
    }

}