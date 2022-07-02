<?php

declare(strict_types = 1);

namespace vale\sage\demonic\enchants\enchantments\type\legendary;

use Cassandra\Exception\ValidationException;
use pocketmine\entity\effect\Effect;
use pocketmine\entity\effect\EffectInstance;
use pocketmine\entity\effect\VanillaEffects;
use pocketmine\event\entity\EntityDamageByEntityEvent;
use pocketmine\item\enchantment\ItemFlags;
use pocketmine\player\Player;
use vale\sage\demonic\enchants\CustomEnchant;
use vale\sage\demonic\enchants\CustomEnchantIds;

class Drunk extends CustomEnchant {

    public function __construct() {
        parent::__construct(
            "Drunk",
            CustomEnchantIds::DRUNK,
            "Slowness and slow swinging with a chance to give buffed strength.",
            4,
            ItemFlags::HEAD,
            self::LEGENDARY,
            self::OFFENSIVE,
            self::ENTITY_DAMAGE_BY_ENTITY,
            self::HELMET
        );

        $this->callable = function (EntityDamageByEntityEvent $event, int $level) : void {
            $entity = $event->getEntity();
            $damager = $event->getDamager();

            if($event->isCancelled() || !$entity instanceof Player || !$damager instanceof Player) return;

            if(mt_rand(0, 100) <= $level * 2.5) {
                if($level > 2) {
                    $amp = 2;
                } else {
                    $amp = $level;
                }

                if($level === 4) $level = 3;

                $entity->getEffects()->add(new EffectInstance(VanillaEffects::SLOWNESS(), $level * 20, $amp));
                $entity->getEffects()->add(new EffectInstance(VanillaEffects::MINING_FATIGUE(), $level * 20, $amp));
                $entity->getEffects()->add(new EffectInstance(VanillaEffects::STRENGTH(), $level * 20, $level));
            }
        };
    }

}