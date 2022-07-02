<?php

declare(strict_types = 1);

namespace vale\sage\demonic\enchants\enchantments\type\elite;

use pocketmine\entity\effect\Effect;
use pocketmine\event\entity\EntityDamageByEntityEvent;
use pocketmine\item\enchantment\ItemFlags;
use pocketmine\player\Player;
use vale\sage\demonic\enchants\CustomEnchant;
use vale\sage\demonic\enchants\CustomEnchantIds;

class Shackle extends CustomEnchant {

    public function __construct() {
        parent::__construct(
            "Shackle",
            CustomEnchantIds::SHACKLE,
            "Chance to prevent mobs spawned from mob spawners from suffering from knockback from your attacks.",
            3,
            ItemFlags::AXE | ItemFlags::SWORD,
            self::ELITE,
            self::OFFENSIVE,
            self::ENTITY_DAMAGE_BY_ENTITY,
            self::SWORD | self::AXE
        );

        $this->callable = function (EntityDamageByEntityEvent $event, int $level) : void {
            $entity = $event->getEntity();
            $damager = $event->getDamager();

            if($event->isCancelled() || $entity instanceof Player || !$damager instanceof Player) return;

            if(mt_rand(0, 100) <= $level * 4) {
                $event->setKnockBack(0);
            }
        };
    }

}