<?php

declare(strict_types = 1);

namespace vale\sage\demonic\enchants\enchantments\type\unique;

use pocketmine\entity\effect\Effect;
use pocketmine\entity\effect\EffectInstance;
use pocketmine\entity\effect\VanillaEffects;
use pocketmine\event\entity\ProjectileHitEntityEvent;
use pocketmine\item\enchantment\ItemFlags;
use pocketmine\player\Player;
use vale\sage\demonic\enchants\CustomEnchant;
use vale\sage\demonic\enchants\CustomEnchantIds;

class Virus extends CustomEnchant {

    public function __construct() {
        parent::__construct(
            "Virus",
            CustomEnchantIds::VIRUS,
            "Multiplies all Wither and Poison damage the affected target recieves and has a chance to remove regeneration effects on hit.",
            4,
            ItemFlags::BOW,
            self::UNIQUE,
            self::OFFENSIVE,
            self::PROJECTILE_ENTITY,
            self::BOW_2
        );

        $this->callable = function (ProjectileHitEntityEvent $event, int $level) : void {
            $owner = $event->getEntity()->getOwningEntity();
            $hit = $event->getEntityHit();

            if(!$owner instanceof Player || !$hit instanceof Player) return;

            if($hit->getEffects()->has(VanillaEffects::WITHER()) && $hit->getEffects()->get(VanillaEffects::WITHER())->getEffectLevel() < 3) $hit->getEffects()->add(new EffectInstance(VanillaEffects::WITHER(), $hit->getEffects()->get(VanillaEffects::WITHER())->getDuration(), $hit->getEffects()->get(VanillaEffects::WITHER())->getEffectLevel() + 1));
            if($hit->getEffects()->has(VanillaEffects::POISON()) && $hit->getEffects()->get(VanillaEffects::POISON())->getEffectLevel() < 3) $hit->getEffects()->add(new EffectInstance(VanillaEffects::POISON(), $hit->getEffects()->get(VanillaEffects::POISON())->getDuration(), $hit->getEffects()->get(VanillaEffects::POISON())->getEffectLevel() + 1));

            if(mt_rand(0, 100) <= $level * 2) {
                if($hit->getEffects()->has(VanillaEffects::REGENERATION())) $hit->getEffects()->remove(VanillaEffects::REGENERATION());
            }
        };
    }

}