<?php

declare(strict_types = 1);

namespace vale\sage\demonic\enchants\enchantments\type\ultimate;

use pocketmine\entity\effect\Effect;
use pocketmine\event\entity\EntityDamageByEntityEvent;
use pocketmine\item\enchantment\ItemFlags;
use pocketmine\player\Player;
use vale\sage\demonic\enchants\CustomEnchant;
use vale\sage\demonic\enchants\CustomEnchantIds;

class Enrage extends CustomEnchant {

    public function __construct() {
        parent::__construct(
            "Enrage",
            CustomEnchantIds::ENRAGE,
            "The lower your HP is, the more damage you deal.",
            3,
            ItemFlags::SWORD,
            self::ULTIMATE,
            self::OFFENSIVE,
            self::ENTITY_DAMAGE_BY_ENTITY,
            self::SWORD
        );

        $this->callable = function (EntityDamageByEntityEvent $event, int $level) : void {
            $c = 1.0;
            $entity = $event->getEntity();
            $damager = $event->getDamager();

            if($event->isCancelled() || !$entity instanceof Player || !$damager instanceof Player) return;

            if($entity->getHealth() <= $entity->getMaxHealth()) $c = 1.05;
            if($entity->getHealth() <= ($entity->getMaxHealth() * 0.66)) $c = 1.1;
            if($entity->getHealth() <= ($entity->getMaxHealth() * 0.33)) $c = 1.15;

            $event->setBaseDamage($event->getBaseDamage() * $c);
        };
    }

}