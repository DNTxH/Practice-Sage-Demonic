<?php

declare(strict_types = 1);

namespace vale\sage\demonic\enchants\enchantments\type\elite;

use pocketmine\entity\effect\Effect;
use pocketmine\event\entity\EntityDamageByEntityEvent;
use pocketmine\item\enchantment\ItemFlags;
use pocketmine\player\Player;
use vale\sage\demonic\enchants\CustomEnchant;
use vale\sage\demonic\enchants\CustomEnchantIds;
use vale\sage\demonic\enchants\enchantments\type\unique\SelfDestruct;

class Trickster extends CustomEnchant {

    public function __construct() {
        parent::__construct(
            "Trickster",
            CustomEnchantIds::TRICKSTER,
            "When hit you have a chance to teleport directly behind your opponent and take them by surprise.",
            8,
            ItemFlags::ARMOR,
            self::ELITE,
            self::DEFENSIVE,
            self::ENTITY_DAMAGE_BY_ENTITY,
            self::ARMOR
        );

        $this->callable = function (EntityDamageByEntityEvent $event, int $level) : void {
            $entity = $event->getEntity();
            $damager = $event->getDamager();

            if($event->isCancelled() || !$entity instanceof Player || !$damager instanceof Player) return;

            if(mt_rand(0, 100) <= $level) {
                $damager->teleport($entity->getDirectionVector()->multiply(-1));
            }
        };
    }

}