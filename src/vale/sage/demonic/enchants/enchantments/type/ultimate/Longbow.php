<?php

declare(strict_types = 1);

namespace vale\sage\demonic\enchants\enchantments\type\ultimate;

use pocketmine\entity\effect\Effect;
use pocketmine\event\entity\EntityDamageByEntityEvent;
use pocketmine\item\Bow;
use pocketmine\item\enchantment\ItemFlags;
use pocketmine\player\Player;
use vale\sage\demonic\enchants\CustomEnchant;
use vale\sage\demonic\enchants\CustomEnchantIds;

class Longbow extends CustomEnchant {

    public function __construct() {
        parent::__construct(
            "Longbow",
            CustomEnchantIds::LONGBOW,
            "Greatly increases damage dealt to enemy players that have a bow in their hands.",
            4,
            ItemFlags::BOW,
            self::ULTIMATE,
            self::OFFENSIVE,
            self::ENTITY_DAMAGE_BY_ENTITY,
            self::BOW_2
        );

        $this->callable = function (EntityDamageByEntityEvent $event, int $level) : void {
            $increase = 1 + (0.075 * $level);
            $entity = $event->getEntity();
            $damager = $event->getDamager();

            if($event->isCancelled() || !$entity instanceof Player || !$damager instanceof Player) return;

            if($entity->getInventory()->getItemInHand() instanceof Bow) $event->setBaseDamage($event->getBaseDamage() * $increase);
        };
    }

}