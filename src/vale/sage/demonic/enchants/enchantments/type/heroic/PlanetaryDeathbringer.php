<?php

declare(strict_types = 1);

namespace vale\sage\demonic\enchants\enchantments\type\heroic;

use pocketmine\entity\effect\Effect;
use pocketmine\event\entity\EntityDamageByEntityEvent;
use pocketmine\item\enchantment\ItemFlags;
use pocketmine\player\Player;
use vale\sage\demonic\enchants\CustomEnchantIds;

class PlanetaryDeathbringer extends HeroicCustomEnchant {

    public function __construct() {
        parent::__construct(
            "Planetary Deathbringer",
            CustomEnchantIds::PLANETARYDEATHBRINGER,
            "An increased chance to deal 2.5x damage. Requires Deathbringer III enchant on item to apply.",
            3,
            ItemFlags::ARMOR,
            self::HEROIC,
            self::OFFENSIVE,
            self::ENTITY_DAMAGE_BY_ENTITY,
            self::ARMOR,
            CustomEnchantIds::DEATHBRINGER
        );

        $this->callable = function (EntityDamageByEntityEvent $event, int $level) : void {
            $entity = $event->getEntity();
            $damager = $event->getDamager();

            if($event->isCancelled() || !$entity instanceof Player || !$damager instanceof Player) return;

            if(mt_rand(0, 100) <= $level * 3) {
                $event->setBaseDamage($event->getBaseDamage() * 2.5);
            }
        };
    }


}