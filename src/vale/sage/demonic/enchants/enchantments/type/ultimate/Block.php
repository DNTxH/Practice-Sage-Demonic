<?php

declare(strict_types = 1);

namespace vale\sage\demonic\enchants\enchantments\type\ultimate;

use pocketmine\entity\effect\Effect;
use pocketmine\event\entity\EntityDamageByEntityEvent;
use pocketmine\item\enchantment\ItemFlags;
use pocketmine\player\Player;
use vale\sage\demonic\enchants\CustomEnchant;
use vale\sage\demonic\enchants\CustomEnchantIds;
use vale\sage\demonic\enchants\enchantments\type\heroic\MaliciouslyCorrupt;

class Block extends CustomEnchant {

    public function __construct() {
        parent::__construct(
            "Block",
            CustomEnchantIds::BLOCK,
            "A chance to increase damage and redirect an attack.",
            3,
            ItemFlags::SWORD,
            self::ULTIMATE,
            self::DEFENSIVE,
            self::ENTITY_DAMAGE_BY_ENTITY,
            self::SWORD
        );

        $this->callable = function (EntityDamageByEntityEvent $event, int $level) : void {
            $entity = $event->getEntity();
            $damager = $event->getDamager();

            if($event->isCancelled() || !$entity instanceof Player || !$damager instanceof Player) return;

            $multi = 1;

            if(in_array($damager->getUniqueId()->toString(), MaliciouslyCorrupt::$maliciouslyCorrupted)) $multi = 0.8;

            if(mt_rand(0, 100) <= $level * 2 * 0.8) {
                $multi = $level * 0.025;

                $event->setBaseDamage($event->getBaseDamage() * $multi);
                $damager->setHealth($damager->getHealth() - $event->getFinalDamage());
                $event->cancel();
            }
        };
    }


}