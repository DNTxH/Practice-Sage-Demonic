<?php

declare(strict_types = 1);

namespace vale\sage\demonic\enchants\enchantments\type\heroic;

use pocketmine\entity\effect\Effect;
use pocketmine\event\entity\EntityDamageByEntityEvent;
use pocketmine\item\enchantment\ItemFlags;
use pocketmine\player\Player;
use vale\sage\demonic\enchants\CustomEnchantIds;
use vale\sage\demonic\enchants\enchantments\type\legendary\Overwhelm;

class ReflectiveBlock extends HeroicCustomEnchant {

    public function __construct() {
        parent::__construct(
            "Reflective Block",
            CustomEnchantIds::REFLECTIVEBLOCK,
            "A high chance to greatly decrease or completely negate incoming damage while blocking, and to reflect an incoming attack back on the attacker whether you are blocking or not.",
            3,
            ItemFlags::SWORD,
            self::HEROIC,
            self::DEFENSIVE,
            self::ENTITY_DAMAGE_BY_ENTITY,
            self::SWORD,
            CustomEnchantIds::BLOCK
        );

        $this->callable = function (EntityDamageByEntityEvent $event, int $level) : void {
            $entity = $event->getEntity();
            $damager = $event->getDamager();
            $multi = 0.33;

            if($event->isCancelled() || !$entity instanceof Player || !$damager instanceof Player) return;

            if(in_array($damager->getUniqueId()->toString(), Overwhelm::$overwhelmed)) return;

            if(mt_rand(0, 100) <= 8 * $level) {
                if($level === 3) $multi = 0;
                if($level === 2) $multi = 0.25;
                if($level === 1) $multi = 0.5;

                $damager->setHealth($damager->getHealth() - $event->getFinalDamage());
                $event->setBaseDamage($event->getBaseDamage() * $multi);
                $event->cancel();
            }
        };
    }
}