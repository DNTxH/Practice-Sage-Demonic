<?php

declare(strict_types = 1);

namespace vale\sage\demonic\enchants\enchantments\type\legendary;

use pocketmine\entity\effect\Effect;
use pocketmine\event\entity\EntityDamageByEntityEvent;
use pocketmine\item\enchantment\ItemFlags;
use pocketmine\player\Player;
use vale\sage\demonic\enchants\CustomEnchant;
use vale\sage\demonic\enchants\CustomEnchantIds;

class Lifesteal extends CustomEnchant {

    public function __construct() {
        parent::__construct(
            "Lifesteal",
            CustomEnchantIds::LIFESTEAL,
            "A chance to regain health when attacking.",
            5,
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

            if(mt_rand(0, 100) <= ($level * 2.5)) {
                $entity->setHealth(($event->getFinalDamage() / 5) * $level);
            }
        };
    }
}