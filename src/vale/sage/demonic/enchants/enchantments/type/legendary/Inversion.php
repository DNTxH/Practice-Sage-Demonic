<?php

declare(strict_types=1);

namespace vale\sage\demonic\enchants\enchantments\type\legendary;

use pocketmine\entity\effect\Effect;
use pocketmine\event\entity\EntityDamageByEntityEvent;
use pocketmine\item\enchantment\ItemFlags;
use pocketmine\player\Player;
use vale\sage\demonic\enchants\CustomEnchant;
use vale\sage\demonic\enchants\CustomEnchantIds;

class Inversion extends CustomEnchant {

    public function __construct() {
        parent::__construct(
            "Inversion",
            CustomEnchantIds::INVERSION,
            "Damage dealt to you has a % chance to be blocked and heal you for 1-3 HP instead.",
            4,
            ItemFlags::SWORD,
            self::LEGENDARY,
            self::DEFENSIVE,
            self::ENTITY_DAMAGE_BY_ENTITY,
            self::SWORD
        );

        $this->callable = function (EntityDamageByEntityEvent $event, int $level) : void {
            $entity = $event->getEntity();
            $damager = $event->getDamager();

            if($event->isCancelled() || !$entity instanceof Player || !$damager instanceof Player) return;

            if(mt_rand(0, 100) <= $level * 2.5) {
                $event->setBaseDamage(0.0);

                if($level === 4) $level = 3;

                $entity->setHealth($entity->getHealth() + ($level * 2));
            }
        };
    }


}