<?php

declare(strict_types = 1);

namespace vale\sage\demonic\enchants\enchantments\type\ultimate;

use pocketmine\entity\effect\Effect;
use pocketmine\event\entity\EntityDamageByEntityEvent;
use pocketmine\item\enchantment\ItemFlags;
use pocketmine\player\Player;
use vale\sage\demonic\enchants\CustomEnchant;
use vale\sage\demonic\enchants\CustomEnchantIds;

class Assassin extends CustomEnchant {

    public function __construct() {
        parent::__construct(
            "Assassin",
            CustomEnchantIds::ASSASSIN,
            "The closer you are to your enemy, the more damage you deal (up to 1.25x). However, if you are more than 2 blocks away, you will deal LESS damage than normal.",
            5,
            ItemFlags::SWORD,
            self::ULTIMATE,
            self::OFFENSIVE,
            self::ENTITY_DAMAGE_BY_ENTITY,
            self::SWORD
        );

        $this->callable = function (EntityDamageByEntityEvent $event, int $level) : void {
            $entity = $event->getEntity();
            $damager = $event->getDamager();

            if($event->isCancelled() || !$entity instanceof Player || !$damager instanceof Player) return;

            if($entity->getPosition()->distance($damager->getPosition()) <= 2.0) {
                $multi = ($level * 0.05) + 1;
                $event->setBaseDamage($event->getBaseDamage() * $multi);
            } else {
                $multi = 1 - ($level * 0.05);
                $event->setBaseDamage($event->getBaseDamage() * $multi);
            }
        };
    }

}