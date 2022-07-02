<?php

declare(strict_types = 1);

namespace vale\sage\demonic\enchants\enchantments\type\elite;

use pocketmine\entity\effect\Effect;
use pocketmine\entity\effect\EffectInstance;
use pocketmine\entity\effect\VanillaEffects;
use pocketmine\event\entity\EntityDamageByEntityEvent;
use pocketmine\item\enchantment\ItemFlags;
use pocketmine\player\Player;
use vale\sage\demonic\enchants\CustomEnchant;
use vale\sage\demonic\enchants\CustomEnchantIds;

class Voodoo extends CustomEnchant {

    public function __construct() {
        parent::__construct(
            "Voodoo",
            CustomEnchantIds::VOODOO,
            "Gives a chance to deal weakness.",
            3,
            ItemFlags::ARMOR,
            self::ELITE,
            self::DEFENSIVE,
            self::ENTITY_DAMAGE_BY_ENTITY,
            self::ARMOR
        );

        $this->callable = function (EntityDamageByEntityEvent $event, int $level) : void {
            $entity = $event->getEntity();
            $damager = $event->getDamager();

            if($event->isCancelled() || !$entity instanceof Player || !$damager instanceof Player) return;

            if(mt_rand(0, 100) <= $level * 2) {
                if(!$entity->getEffects()->has(VanillaEffects::WEAKNESS())) $entity->getEffects()->add(new EffectInstance(VanillaEffects::WEAKNESS(), $level * 20, $level));
            }
        };
    }

}