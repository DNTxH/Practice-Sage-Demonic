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

class Berserk extends CustomEnchant {

    public function __construct() {
        parent::__construct(
            "Berserk",
            CustomEnchantIds::BERSERK,
            "A chance of strength and mining fatigue.",
            5,
            ItemFlags::AXE,
            self::UNIQUE,
            self::OFFENSIVE,
            self::ENTITY_DAMAGE_BY_ENTITY,
            self::AXE
        );

        $this->callable = function (EntityDamageByEntityEvent $event, int $level) : void {
            $entity = $event->getEntity();
            $damager = $event->getDamager();

            if($event->isCancelled() || !$entity instanceof Player || !$damager instanceof Player) return;

            if(mt_rand(1, 100) <= $level * 2) {
                if($level > 3) {
                    $amp = 2;
                } else {
                    $amp = 1;
                }

                if(!$damager->getEffects()->has(VanillaEffects::STRENGTH())) $damager->getEffects()->add(new EffectInstance(VanillaEffects::STRENGTH(), $level * 30, $amp, false));
                $damager->getEffects()->add(new EffectInstance(VanillaEffects::MINING_FATIGUE(), 30, false));
            }
        };
    }


}