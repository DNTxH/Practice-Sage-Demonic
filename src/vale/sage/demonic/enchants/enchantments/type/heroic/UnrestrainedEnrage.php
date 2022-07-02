<?php

declare(strict_types = 1);

namespace vale\sage\demonic\enchants\enchantments\type\heroic;

use pocketmine\entity\effect\Effect;
use pocketmine\event\entity\EntityDamageByEntityEvent;
use pocketmine\item\enchantment\ItemFlags;
use pocketmine\player\Player;
use vale\sage\demonic\enchants\CustomEnchantIds;

class UnrestrainedEnrage extends HeroicCustomEnchant {

    public function __construct() {
        parent::__construct(
            "Unrestrained Enrage",
            CustomEnchantIds::UNRESTRAINEDENRAGE,
            "Deal stacking amounts of bonus outgoing damage when your HP is less than 100%, 75%, 50% and 25%.",
            3,
            ItemFlags::SWORD,
            self::HEROIC,
            self::OFFENSIVE,
            self::ENTITY_DAMAGE_BY_ENTITY,
            self::SWORD,
            CustomEnchantIds::ENRAGE
        );

        $this->callable = function (EntityDamageByEntityEvent $event, int $level) : void {
            $c = 1.0;
            $entity = $event->getEntity();
            $damager = $event->getDamager();

            if($event->isCancelled() || !$entity instanceof Player || !$damager instanceof Player) return;

            if($entity->getHealth() <= $entity->getMaxHealth()) $c = 1.125;
            if($entity->getHealth() <= ($entity->getMaxHealth() * 0.75)) $c = 1.25;
            if($entity->getHealth() <= ($entity->getMaxHealth() * 0.5)) $c = 1.375;
            if($entity->getHealth() <= ($entity->getMaxHealth() * 0.25)) $c = 1.5;

            $event->setBaseDamage($event->getBaseDamage() * $c);
        };
    }

}