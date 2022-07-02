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

class Wither extends CustomEnchant {

    public function __construct() {
        parent::__construct(
            "Wither",
            CustomEnchantIds::WITHER,
            "A chance to give the wither effect.",
            5,
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

            if(mt_rand(0, 100) <= $level) {
                if($level >= 3) {
                    $amp = 3;
                } else {
                    $amp = $level;
                }

                if(!$damager->getEffects()->has(VanillaEffects::WITHER())) $damager->getEffects()->add(new EffectInstance(VanillaEffects::WITHER(), $amp * 15, $amp));
            }
        };
    }

}