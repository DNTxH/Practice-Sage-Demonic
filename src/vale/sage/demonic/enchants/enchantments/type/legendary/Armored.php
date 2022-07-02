<?php

declare(strict_types = 1);

namespace vale\sage\demonic\enchants\enchantments\type\legendary;

use pocketmine\entity\effect\Effect;
use pocketmine\event\entity\EntityDamageByEntityEvent;
use pocketmine\item\enchantment\ItemFlags;
use pocketmine\item\Sword;
use pocketmine\player\Player;
use vale\sage\demonic\enchants\CustomEnchant;
use vale\sage\demonic\enchants\CustomEnchantIds;

class Armored extends CustomEnchant {

    public function __construct() {
        parent::__construct(
            "Armored",
            CustomEnchantIds::ARMORED,
            "Decreases damage from enemy swords by 1.85% per level.",
            4,
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

            if($damager->getInventory()->getItemInHand() instanceof Sword) $event->setBaseDamage($event->getBaseDamage() * (1 - (0.0185 * $level)));
        };
    }

}