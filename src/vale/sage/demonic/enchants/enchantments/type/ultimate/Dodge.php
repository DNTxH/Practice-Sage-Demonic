<?php

declare(strict_types = 1);

namespace vale\sage\demonic\enchants\enchantments\type\ultimate;

use pocketmine\entity\effect\Effect;
use pocketmine\event\entity\EntityDamageByEntityEvent;
use pocketmine\item\enchantment\ItemFlags;
use pocketmine\player\Player;
use vale\sage\demonic\enchants\CustomEnchant;
use vale\sage\demonic\enchants\CustomEnchantIds;

class Dodge extends CustomEnchant {

    public function __construct() {
        parent::__construct(
            "Dodge",
            CustomEnchantIds::DODGE,
            "Chance to dodge physical enemy attacks, increased chance if sneaking.",
            5,
            ItemFlags::ARMOR,
            self::ULTIMATE,
            self::DEFENSIVE,
            self::ENTITY_DAMAGE_BY_ENTITY,
            self::ARMOR
        );

        $this->callable = function (EntityDamageByEntityEvent $event, int $level) : void {
            $entity = $event->getEntity();
            $damager = $event->getDamager();

            if($event->isCancelled() || !$entity instanceof Player || !$damager instanceof Player) return;

            if($entity->isSneaking()) {
                $c = $level * 2;
            } else {
                $c = $level * 1.5;
            }

            if(mt_rand(0, 100) <= $c) {
                $event->cancel();
            }
        };
    }
}