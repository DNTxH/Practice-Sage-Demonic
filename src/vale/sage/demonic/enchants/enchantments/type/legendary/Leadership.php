<?php

declare(strict_types=1);

namespace vale\sage\demonic\enchants\enchantments\type\legendary;

use pocketmine\entity\effect\Effect;
use pocketmine\event\entity\EntityDamageByEntityEvent;
use pocketmine\item\enchantment\ItemFlags;
use pocketmine\player\Player;
use vale\sage\demonic\enchants\CustomEnchant;
use vale\sage\demonic\enchants\CustomEnchantIds;

class Leadership extends CustomEnchant {

    public function __construct() {
        parent::__construct(
            "Leadership",
            CustomEnchantIds::LEADERSHIP,
            "The more allies near you, the more damage you deal, the higher the level, the greater the radius.",
            5,
            ItemFlags::ARMOR,
            self::LEGENDARY,
            self::OFFENSIVE,
            self::ENTITY_DAMAGE_BY_ENTITY,
            self::ARMOR
        );

        $this->callable = function (EntityDamageByEntityEvent $event, int $level) : void {
            $entity = $event->getEntity();
            $damager = $event->getDamager();
            $multi = 1;

            if($event->isCancelled() || !$entity instanceof Player || !$damager instanceof Player) return;

            foreach($damager->getWorld()->getNearbyEntities($damager->getBoundingBox()->expandedCopy($level * 3, $level * 3, $level * 3)) as $e) {
                if(!$e instanceof Player) continue;
                $multi += 0.05;
            }

            $event->setBaseDamage($event->getBaseDamage() * $multi);
        };
    }

}