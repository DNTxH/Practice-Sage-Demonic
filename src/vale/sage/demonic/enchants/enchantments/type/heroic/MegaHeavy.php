<?php

declare(strict_types = 1);

namespace vale\sage\demonic\enchants\enchantments\type\heroic;

use pocketmine\entity\effect\Effect;
use pocketmine\event\entity\EntityDamageByEntityEvent;
use pocketmine\item\enchantment\ItemFlags;
use pocketmine\player\Player;
use vale\sage\demonic\enchants\CustomEnchantIds;

class MegaHeavy extends HeroicCustomEnchant {

    public function __construct() {
        parent::__construct(
            "Mega Heavy",
            CustomEnchantIds::MEGAHEAVY,
            "Decreases Damage from enemy bows by 10% plus an additional 2% per level! This enchantment is also stackable",
            5,
            ItemFlags::ARMOR,
            self::HEROIC,
            self::DEFENSIVE,
            self::ENTITY_DAMAGE_BY_ENTITY,
            self::ARMOR,
            CustomEnchantIds::HEAVY
        );

        $this->callable = function (EntityDamageByEntityEvent $event, int $level) : void {
            $entity = $event->getEntity();
            $damager = $event->getDamager();
            $multi = 1 - (10 + ($level * 0.02));

            if($event->isCancelled() || !$entity instanceof Player || !$damager instanceof Player) return;

            if($event->getCause() === EntityDamageByEntityEvent::CAUSE_PROJECTILE) {
                $event->setBaseDamage($event->getBaseDamage() * $multi);
            }
        };
    }

}