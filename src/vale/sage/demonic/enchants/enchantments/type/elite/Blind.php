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
use vale\sage\demonic\enchants\enchantments\EnchantmentsManager;

class Blind extends CustomEnchant {

    public function __construct() {
        parent::__construct(
            "Blind",
            CustomEnchantIds::BLIND,
            "A Chance of causing blindness when attacking.",
            3,
            ItemFlags::BOW | ItemFlags::SWORD,
            self::ELITE,
            self::OFFENSIVE,
            self::ENTITY_DAMAGE_BY_ENTITY,
            self::SWORD
        );

        $this->callable = function (EntityDamageByEntityEvent $event, int $level) : void {
            $entity = $event->getEntity();
            $damager = $event->getDamager();

            if($event->isCancelled() || !$entity instanceof Player || !$damager instanceof Player) return;

            if(mt_rand(0, 100) <= $level * 2) {
                if(!$entity->getEffects()->has(VanillaEffects::BLINDNESS())) {
                    $has = false;
                    $lvl = 0;

                    foreach($entity->getArmorInventory()->getContents() as $content) {
                        if($content->hasEnchantment(EnchantmentsManager::getEnchantment(CustomEnchantIds::CLARITY))) {
                            $has = true;
                            $lvl = $content->getEnchantmentLevel(EnchantmentsManager::getEnchantment(CustomEnchantIds::CLARITY));
                        }
                    }

                    if($has) {
                        if($level > $lvl) $entity->getEffects()->add(new EffectInstance(VanillaEffects::BLINDNESS(), $level * 20, $level));
                    } else {
                        $entity->getEffects()->add(new EffectInstance(VanillaEffects::BLINDNESS(), $level * 20, $level));
                    }
                }
            }
        };
    }

}