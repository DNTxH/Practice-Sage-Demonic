<?php

declare(strict_types = 1);

namespace vale\sage\demonic\enchants\enchantments\type\unique;

use pocketmine\entity\effect\Effect;
use pocketmine\entity\effect\EffectInstance;
use pocketmine\entity\effect\VanillaEffects;
use pocketmine\event\entity\EntityDamageByEntityEvent;
use pocketmine\item\enchantment\ItemFlags;
use pocketmine\player\Player;
use vale\sage\demonic\enchants\CustomEnchant;
use vale\sage\demonic\enchants\CustomEnchantIds;

class Curse extends CustomEnchant {

    public function __construct() {
        parent::__construct(
            "Curse",
            CustomEnchantIds::CURSE,
            "Gives strength, slowness and resistance at low hp.",
            5,
            ItemFlags::TORSO,
            self::UNIQUE,
            self::DEFENSIVE,
            self::ENTITY_DAMAGE_BY_ENTITY,
            self::CHESTPLATE
        );

        $this->callable = function (EntityDamageByEntityEvent $event, int $level) : void {
            $entity = $event->getEntity();
            $damager = $event->getDamager();

            if($event->isCancelled() || !$entity instanceof Player || !$damager instanceof Player) return;

            if($entity->getHealth() <= 6) {
                $entity->getEffects()->add(new EffectInstance(VanillaEffects::STRENGTH(), $level * 20, 2));
                $entity->getEffects()->add(new EffectInstance(VanillaEffects::SLOWNESS(), $level * 20, 2));
                $entity->getEffects()->add(new EffectInstance(VanillaEffects::RESISTANCE(), $level * 20, 2));
            }
        };
    }


}