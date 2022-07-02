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

class Paralyze extends CustomEnchant {

    public function __construct() {
        parent::__construct(
            "Paralyze",
            CustomEnchantIds::PARALYZE,
            "Has a chance to give slowness and slow swinging. Also inflicts small amounts of direct damage on proc.",
            4,
            ItemFlags::SWORD,
            self::ELITE,
            self::OFFENSIVE,
            self::ENTITY_DAMAGE_BY_ENTITY,
            self::SWORD
        );

        $this->callable = function (EntityDamageByEntityEvent $event, int $level) : void {
            $entity = $event->getEntity();
            $damager = $event->getDamager();

            if($event->isCancelled() || !$entity instanceof Player || !$damager instanceof Player) return;

            $entity->setHealth($entity->getHealth() - 0.15);

            if(mt_rand(0, 100) <= $level * 2) {

                if($level >= 3) {
                    $amp = 2;
                } else {
                    $amp = 1;
                }

                if(!$entity->getEffects()->has(VanillaEffects::SLOWNESS())) $entity->getEffects()->add(new EffectInstance(VanillaEffects::SLOWNESS(), 20 * $level, $amp));
                if(!$entity->getEffects()->has(VanillaEffects::MINING_FATIGUE())) $entity->getEffects()->add(new EffectInstance(VanillaEffects::MINING_FATIGUE(), 20 * $level, $amp));
            }
        };
    }

}