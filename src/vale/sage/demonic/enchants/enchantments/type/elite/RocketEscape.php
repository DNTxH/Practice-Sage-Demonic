<?php

declare(strict_types = 1);

namespace vale\sage\demonic\enchants\enchantments\type\elite;

use pocketmine\entity\effect\Effect;
use pocketmine\event\entity\EntityDamageByEntityEvent;
use pocketmine\item\enchantment\ItemFlags;
use pocketmine\player\Player;
use vale\sage\demonic\enchants\CustomEnchant;
use vale\sage\demonic\enchants\CustomEnchantIds;
use vale\sage\demonic\enchants\enchantments\type\soul\Sabotage;

class RocketEscape extends CustomEnchant {

    public function __construct() {
        parent::__construct(
            "Rocket Escape",
            CustomEnchantIds::ROCKETESCAPE,
            "Blast off into the air at low HP.",
            3,
            ItemFlags::FEET,
            self::ELITE,
            self::DEFENSIVE,
            self::ENTITY_DAMAGE_BY_ENTITY,
            self::BOOTS
        );

        $this->callable = function (EntityDamageByEntityEvent $event, int $level) : void {
            $entity = $event->getEntity();
            $damager = $event->getDamager();

            if($event->isCancelled() || !$entity instanceof Player || !$damager instanceof Player) return;
            if(in_array($entity->getUniqueId()->toString(), Sabotage::$sabotaged)) return;

            if($entity->getHealth() <= 4.0) {
                $entity->knockBack($level * 3, $level * 3, $level, $level * 3);
            }
        };
    }
}