<?php

declare(strict_types = 1);

namespace vale\sage\demonic\enchants\enchantments\type\ultimate;

use pocketmine\event\entity\EntityDamageEvent;
use pocketmine\item\enchantment\ItemFlags;
use pocketmine\player\Player;
use vale\sage\demonic\enchants\CustomEnchant;
use vale\sage\demonic\enchants\CustomEnchantIds;

class CreeperArmor extends CustomEnchant {

    public function __construct() {
        parent::__construct(
            "Creeper Armor",
            CustomEnchantIds::CREEPERARMOR,
            "Immune to explosive damage, at higher levels you take no knockback from them and they have a chance to heal you.",
            3,
            ItemFlags::ARMOR,
            self::ULTIMATE,
            self::DEFENSIVE,
            self::ENTITY_DAMAGE,
            self::ARMOR
        );

        $this->callable = function (EntityDamageEvent $event, int $level) : void {
            $entity = $event->getEntity();

            if($event->isCancelled() || !$entity instanceof Player) return;

            if($event->getCause() === EntityDamageEvent::CAUSE_BLOCK_EXPLOSION || $event->getCause() === EntityDamageEvent::CAUSE_ENTITY_EXPLOSION) {
                if($level > 3) {
                    if(mt_rand(0, 100) <= $level * 2.5) $entity->setHealth($entity->getHealth() + $event->getFinalDamage());
                }

                $event->cancel();
            }
        };
    }

}