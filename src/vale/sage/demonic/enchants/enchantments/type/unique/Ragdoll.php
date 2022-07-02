<?php

declare(strict_types = 1);

namespace vale\sage\demonic\enchants\enchantments\type\unique;

use pocketmine\entity\effect\Effect;
use pocketmine\event\entity\EntityDamageByEntityEvent;
use pocketmine\item\enchantment\ItemFlags;
use pocketmine\player\Player;
use vale\sage\demonic\enchants\CustomEnchant;
use vale\sage\demonic\enchants\CustomEnchantIds;

class Ragdoll extends CustomEnchant {

    public function __construct() {
        parent::__construct(
        "Ragdoll",
        CustomEnchantIds::RAGDOLL,
        "Whenever you take damage there is a chance you are pushed far back.",
        4,
        ItemFlags::ARMOR,
        self::UNIQUE,
        self::DEFENSIVE,
        self::ENTITY_DAMAGE_BY_ENTITY,
        self::ARMOR
        );

        $this->callable = function (EntityDamageByEntityEvent $event, int $level) : void {
            $entity = $event->getEntity();
            $damager = $event->getDamager();

            if($event->isCancelled() || !$entity instanceof Player || !$damager instanceof Player) return;

            if(mt_rand(0, 100) <= $level) {
                $entity->knockBack($level * 1.5, $level * 1.5);
            }
        };
    }

}