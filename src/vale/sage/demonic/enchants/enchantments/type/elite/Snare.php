<?php

declare(strict_types = 1);

namespace vale\sage\demonic\enchants\enchantments\type\elite;

use pocketmine\entity\effect\Effect;
use pocketmine\entity\effect\EffectInstance;
use pocketmine\entity\effect\VanillaEffects;
use pocketmine\event\entity\ProjectileHitEntityEvent;
use pocketmine\item\enchantment\ItemFlags;
use pocketmine\player\Player;
use vale\sage\demonic\enchants\CustomEnchant;
use vale\sage\demonic\enchants\CustomEnchantIds;
use vale\sage\demonic\enchants\event\MetaphysicalEvent;

class Snare extends CustomEnchant {

    public function __construct() {
        parent::__construct(
            "Snare",
            CustomEnchantIds::SNARE,
            "Chance to slow and fatigue enemies with projectiles.",
            4,
            ItemFlags::BOW,
            self::ELITE,
            self::OFFENSIVE,
            self::PROJECTILE_ENTITY,
            self::BOW_2
        );

        $this->callable = function (ProjectileHitEntityEvent $event, int $level) : void {
            $hit = $event->getEntityHit();

            if($hit instanceof Player) {
                if(mt_rand(0, 100) <= $level * 2) {
                    if($level > 3) {
                        $amp = 3;
                    } else {
                        $amp = $level;
                    }

                    if(!$hit->getEffects()->has(VanillaEffects::MINING_FATIGUE())) $hit->getEffects()->add(new EffectInstance(VanillaEffects::MINING_FATIGUE(), $level * 20, $amp));

                    $ev = new MetaphysicalEvent($hit);

                    $ev->call();

                    if($ev->isCancelled()) return;

                    if(!$hit->getEffects()->has(VanillaEffects::SLOWNESS())) $hit->getEffects()->add(new EffectInstance(VanillaEffects::SLOWNESS(), $level * 20, $amp));
                }
            }
        };
    }

}