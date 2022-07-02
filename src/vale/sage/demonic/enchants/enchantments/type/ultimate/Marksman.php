<?php

declare(strict_types = 1);

namespace vale\sage\demonic\enchants\enchantments\type\ultimate;

use pocketmine\entity\effect\Effect;
use pocketmine\event\entity\EntityDamageByEntityEvent;
use pocketmine\item\enchantment\ItemFlags;
use pocketmine\player\Player;
use vale\sage\demonic\enchants\CustomEnchant;
use vale\sage\demonic\enchants\CustomEnchantIds;

class Marksman extends CustomEnchant {

    public function __construct() {
        parent::__construct(
            "Marksman",
            CustomEnchantIds::MARKSMAN,
            "Increases damage dealt with bows, this enchantment is stackable.",
            4,
            ItemFlags::ARMOR,
            self::ULTIMATE,
            self::OFFENSIVE,
            self::ENTITY_DAMAGE_BY_ENTITY,
            self::ARMOR
        );

        $this->callable = function (EntityDamageByEntityEvent $event, int $level) : void {
            $entity = $event->getEntity();
            $damager = $event->getDamager();

            if($event->isCancelled() || !$entity instanceof Player || !$damager instanceof Player) return;

            $multi = ($level * 0.02) + 1;

            if($event->getCause() === EntityDamageByEntityEvent::CAUSE_PROJECTILE) {
                $event->setBaseDamage($event->getBaseDamage() * $multi);
            }
        };
    }


}