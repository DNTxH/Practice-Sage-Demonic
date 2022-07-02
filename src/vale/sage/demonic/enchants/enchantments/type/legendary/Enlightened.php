<?php

declare(strict_types = 1);

namespace vale\sage\demonic\enchants\enchantments\type\legendary;

use pocketmine\entity\effect\Effect;
use pocketmine\event\entity\EntityDamageByEntityEvent;
use pocketmine\item\enchantment\ItemFlags;
use pocketmine\player\Player;
use vale\sage\demonic\enchants\CustomEnchant;
use vale\sage\demonic\enchants\CustomEnchantIds;

class Enlightened extends CustomEnchant {

    public function __construct() {
        parent::__construct(
            "Enlightened",
            CustomEnchantIds::ENLIGHTENED,
            "Can heal hearts while taking damage.",
            3,
            ItemFlags::ARMOR,
            self::LEGENDARY,
            self::DEFENSIVE,
            self::ENTITY_DAMAGE_BY_ENTITY,
            self::ARMOR
        );

        $this->callable = function (EntityDamageByEntityEvent $event, int $level) : void {
            $entity = $event->getEntity();
            $damager = $event->getDamager();

            if($event->isCancelled() || !$entity instanceof Player || !$damager instanceof Player) return;

            if(mt_rand(0, 100) <= ($level * 3)) {
                $entity->setHealth($entity->getHealth() + ($level * 2));
            }
        };
    }

}