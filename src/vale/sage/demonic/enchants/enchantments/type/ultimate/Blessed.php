<?php

declare(strict_types = 1);

namespace vale\sage\demonic\enchants\enchantments\type\ultimate;

use pocketmine\entity\effect\Effect;
use pocketmine\event\entity\EntityDamageByEntityEvent;
use pocketmine\item\enchantment\ItemFlags;
use pocketmine\player\Player;
use vale\sage\demonic\enchants\CustomEnchant;
use vale\sage\demonic\enchants\CustomEnchantIds;

class Blessed extends CustomEnchant {

    public function __construct() {
        parent::__construct(
            "Blessed",
            CustomEnchantIds::BLESSED,
            "A chance of removing debuffs.",
            4,
            ItemFlags::AXE,
            self::ULTIMATE,
            self::OFFENSIVE,
            self::ENTITY_DAMAGE_BY_ENTITY,
            self::AXE
        );

        $this->callable = function (EntityDamageByEntityEvent $event, int $level) : void {
            $entity = $event->getEntity();
            $damager = $event->getDamager();

            if($event->isCancelled() || !$entity instanceof Player || !$damager instanceof Player) return;

            if(mt_rand(0, 100) <= $level * 2.5) {
                foreach($entity->getEffects()->all() as $effect) {
                    if($effect->getType()->isBad()) $entity->getEffects()->remove($effect);
                }
            }
        };
    }

}