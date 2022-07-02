<?php

declare(strict_types = 1);

namespace vale\sage\demonic\enchants\enchantments\type\elite;

use pocketmine\entity\effect\EffectInstance;
use pocketmine\entity\effect\VanillaEffects;
use pocketmine\event\entity\EntityDamageByEntityEvent;
use pocketmine\item\enchantment\ItemFlags;
use pocketmine\player\Player;
use vale\sage\demonic\enchants\CustomEnchant;
use vale\sage\demonic\enchants\CustomEnchantIds;

class Execute extends CustomEnchant {

    public function __construct() {
        parent::__construct(
            "Execute",
            CustomEnchantIds::EXECUTE,
            "Damage buff when your target is at low hp.",
            7,
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

            if($level > 2) {
                $amp = 3;
            } else {
                $amp = $level;
            }

            if($entity->getHealth() <= 7) {
                if(!$damager->getEffects()->has(VanillaEffects::STRENGTH())) $damager->getEffects()->add(new EffectInstance(VanillaEffects::STRENGTH(), $level * 10, $amp));
            }
        };
    }


}