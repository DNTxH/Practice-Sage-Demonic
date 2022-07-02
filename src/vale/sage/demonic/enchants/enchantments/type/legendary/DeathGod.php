<?php

declare(strict_types = 1);

namespace vale\sage\demonic\enchants\enchantments\type\legendary;

use pocketmine\entity\effect\Effect;
use pocketmine\event\entity\EntityDamageByEntityEvent;
use pocketmine\item\enchantment\ItemFlags;
use pocketmine\player\Player;
use vale\sage\demonic\enchants\CustomEnchant;
use vale\sage\demonic\enchants\CustomEnchantIds;

class DeathGod extends CustomEnchant {

    public function __construct() {
        parent::__construct(
            "Death God",
            CustomEnchantIds::DEATHGOD,
            "Attacks that bring your HP to (level+4) hearts or lower have a chance to heal you for (level+5) hearts instead.",
            3,
            ItemFlags::HEAD,
            self::LEGENDARY,
            self::DEFENSIVE,
            self::ENTITY_DAMAGE_BY_ENTITY,
            self::HELMET
        );

        $this->callable = function (EntityDamageByEntityEvent $event, int $level) : void {
            $entity = $event->getEntity();
            $damager = $event->getDamager();

            if($event->isCancelled() || !$entity instanceof Player || !$damager instanceof Player) return;

            if(($entity->getHealth() - $event->getFinalDamage()) <= 8.0) {
                if(mt_rand(0, 100) <= ($level * 3)) {
                    $entity->setHealth($entity->getHealth() + ($level + 5));
                }
            }
        };
    }

}