<?php

declare(strict_types = 1);

namespace vale\sage\demonic\enchants\enchantments\type\heroic;

use pocketmine\entity\effect\Effect;
use pocketmine\event\entity\EntityDamageByEntityEvent;
use pocketmine\item\enchantment\ItemFlags;
use pocketmine\player\Player;
use vale\sage\demonic\enchants\CustomEnchantIds;

class DemonicLifesteal extends HeroicCustomEnchant {

    public function __construct() {
        parent::__construct(
            "Demonic Lifesteal",
            CustomEnchantIds::DEMONICLIFESTEAL,
            "Heals much more HP at a greatly increased rate compared to normal Lifesteal.",
            3,
            ItemFlags::SWORD | ItemFlags::AXE,
            self::HEROIC,
            self::OFFENSIVE,
            self::ENTITY_DAMAGE_BY_ENTITY,
            self::SWORD | self::AXE,
            CustomEnchantIds::LIFESTEAL
        );

        $this->callable = function (EntityDamageByEntityEvent $event, int $level) : void {
            $entity = $event->getEntity();
            $damager = $event->getDamager();

            if($event->isCancelled() || !$entity instanceof Player || !$damager instanceof Player) return;

            if(mt_rand(0, 100) <= ($level * 12.5)) {
                $entity->setHealth($event->getFinalDamage() * $level);
            }
        };
    }
}