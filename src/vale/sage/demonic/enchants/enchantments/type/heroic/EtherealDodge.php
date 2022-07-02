<?php

declare(strict_types = 1);

namespace vale\sage\demonic\enchants\enchantments\type\heroic;

use pocketmine\entity\effect\Effect;
use pocketmine\entity\effect\EffectInstance;
use pocketmine\entity\effect\VanillaEffects;
use pocketmine\event\entity\EntityDamageByEntityEvent;
use pocketmine\item\enchantment\ItemFlags;
use pocketmine\player\Player;
use pocketmine\scheduler\ClosureTask;
use vale\sage\demonic\enchants\CustomEnchantIds;
use vale\sage\demonic\Loader;

class EtherealDodge extends HeroicCustomEnchant {

    public function __construct() {
        parent::__construct(
            "Ethereal Dodge",
            CustomEnchantIds::ETHEREALDODGE,
            "Increased proc rate over normal Dodge, with a small chance to gain Speed V for a few seconds on successful dodge. All fall damage is disabled.",
            1,
            ItemFlags::FEET,
            self::HEROIC,
            self::DEFENSIVE,
            self::ENTITY_DAMAGE_BY_ENTITY,
            self::BOOTS,
            CustomEnchantIds::DODGE
        );

        $this->callable = function (EntityDamageByEntityEvent $event, int $level) : void {
            $entity = $event->getEntity();
            $damager = $event->getDamager();

            if($event->isCancelled() || !$entity instanceof Player || !$damager instanceof Player) return;

            if(mt_rand(0, 100) <= 15) {
                $event->cancel();

                if($entity->getEffects()->has(VanillaEffects::SPEED())) {
                    $amp = $entity->getEffects()->get(VanillaEffects::SPEED())->getAmplifier();
                    if($amp < 4) {
                        Loader::getInstance()->getScheduler()->scheduleDelayedTask(new ClosureTask(function () use($amp, $entity) : void {
                            $entity->getEffects()->add(new EffectInstance(VanillaEffects::SPEED(), 2147483647, $amp));
                        }), 60);
                    }
                }

                $entity->getEffects()->add(new EffectInstance(VanillaEffects::SPEED(), 60, 5));
            }
        };
    }

}