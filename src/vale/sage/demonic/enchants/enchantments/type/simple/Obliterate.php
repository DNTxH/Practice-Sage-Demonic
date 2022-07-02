<?php

declare(strict_types = 1);

namespace vale\sage\demonic\enchants\enchantments\type\simple;

use pocketmine\event\entity\EntityDamageByEntityEvent;
use pocketmine\item\enchantment\ItemFlags;
use pocketmine\player\Player;
use vale\sage\demonic\enchants\CustomEnchant;
use vale\sage\demonic\enchants\CustomEnchantIds;

class Obliterate extends CustomEnchant {

    public function __construct() {
        parent::__construct(
            "Obliterate",
            CustomEnchantIds::OBLITERATE,
            "Extreme knockback.",
            5,
            ItemFlags::SWORD | ItemFlags::AXE,
            self::SIMPLE,
            self::OFFENSIVE,
            self::ENTITY_DAMAGE_BY_ENTITY,
            self::SWORD | self::AXE
        );

        $this->callable = function (EntityDamageByEntityEvent $event, int $level) : void {
            $chance = $level * 2;
            $entity = $event->getEntity();
            $damager = $event->getDamager();

            if($event->isCancelled() || !$entity instanceof Player || !$damager instanceof Player) return;

            if(mt_rand(1, 100) <= $chance) {
                $entity->knockBack($level * 3, $level * 3);
            }
        };
    }

}