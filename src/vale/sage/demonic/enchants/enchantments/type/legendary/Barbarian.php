<?php

declare(strict_types = 1);

namespace vale\sage\demonic\enchants\enchantments\type\legendary;

use pocketmine\entity\effect\Effect;
use pocketmine\event\entity\EntityDamageByEntityEvent;
use pocketmine\item\Axe;
use pocketmine\item\enchantment\ItemFlags;
use pocketmine\player\Player;
use vale\sage\demonic\enchants\CustomEnchant;
use vale\sage\demonic\enchants\CustomEnchantIds;

class Barbarian extends CustomEnchant {

    public function __construct() {
        parent::__construct(
            "Barbarian",
            CustomEnchantIds::BARBARIAN,
            "Multiplies damage against players who are wielding an AXE at the time they are hit.",
            4,
            ItemFlags::AXE,
            self::LEGENDARY,
            self::OFFENSIVE,
            self::ENTITY_DAMAGE_BY_ENTITY,
            self::AXE
        );

        $this->callable = function (EntityDamageByEntityEvent $event, int $level) : void {
            $entity = $event->getEntity();
            $damager = $event->getDamager();

            if($event->isCancelled() || !$entity instanceof Player || !$damager instanceof Player) return;

            if($entity->getInventory()->getItemInHand() instanceof Axe) $event->setBaseDamage($event->getBaseDamage() * ($level * 0.05));
        };
    }

}