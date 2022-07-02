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
use vale\sage\demonic\enchants\enchantments\EnchantmentsManager;

class PlagueCarrier extends CustomEnchant {

    public function __construct() {
        parent::__construct(
        "Plague Carrier",
        CustomEnchantIds::PLAGUECARRIER,
        "When near death summons creepers and debuffs to avenge you.",
        8,
        ItemFlags::LEGS,
        self::UNIQUE,
        self::DEFENSIVE,
        self::ENTITY_DAMAGE_BY_ENTITY,
        self::LEGGINGS
        );

        $this->callable = function (EntityDamageByEntityEvent $event, int $level) : void {
            $entity = $event->getEntity();
            $damager = $event->getDamager();

            if(!$entity instanceof Player || !$damager instanceof Player || $event->isCancelled()) return;

            if($entity->getHealth() <= 8.0) {
                if($level > 4) {
                    $amp = 2;
                } else {
                    $amp = 1;
                }

                if(!$damager->getEffects()->has(VanillaEffects::NAUSEA())) $damager->getEffects()->add(new EffectInstance(VanillaEffects::NAUSEA(), $level * 20, $amp, false));
                if(!$damager->getEffects()->has(VanillaEffects::BLINDNESS()))  {
                    $has = false;
                    $lvl = 0;

                    foreach($damager->getArmorInventory()->getContents() as $content) {
                        if($content->hasEnchantment(EnchantmentsManager::getEnchantment(CustomEnchantIds::CLARITY))) {
                            $has = true;
                            $lvl = $content->getEnchantmentLevel(EnchantmentsManager::getEnchantment(CustomEnchantIds::CLARITY));
                        }
                    }

                    if($has) {
                        if($amp > $lvl) $damager->getEffects()->add(new EffectInstance(VanillaEffects::BLINDNESS(), $level * 20, $amp, false));
                    } else {
                        $damager->getEffects()->add(new EffectInstance(VanillaEffects::BLINDNESS(), $level * 20, $amp, false));
                    }
                }
                if(!$damager->getEffects()->has(VanillaEffects::WEAKNESS())) $damager->getEffects()->add(new EffectInstance(VanillaEffects::WEAKNESS(), $level * 20, $amp, false));
            }
        };
    }

}