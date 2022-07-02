<?php

declare(strict_types = 1);

namespace vale\sage\demonic\enchants\enchantments\type\legendary;

use pocketmine\entity\effect\Effect;
use pocketmine\item\enchantment\ItemFlags;
use vale\sage\demonic\enchants\CustomEnchant;
use vale\sage\demonic\enchants\CustomEnchantIds;
use vale\sage\demonic\enchants\enchantments\EnchantmentsManager;
use vale\sage\demonic\enchants\event\EnchantmentActivationEvent;

class Silence extends CustomEnchant {

    public function __construct() {
        parent::__construct(
            "Silence",
            CustomEnchantIds::SILENCE,
            "Chance to stop activation of your enemy's custom enchants.",
            4,
            ItemFlags::AXE | ItemFlags::SWORD,
            self::LEGENDARY,
            self::OFFENSIVE,
            self::ENTITY_DAMAGE_BY_ENTITY,
            self::SWORD | self::AXE
        );

        $this->callable = function (EnchantmentActivationEvent $event, int $level) : void {
            if($event->getPlayer()->getInventory()->getItemInHand()->hasEnchantment(EnchantmentsManager::getEnchantment(CustomEnchantIds::PERFECTSOLITUDE))) {
                $solitudeLevel = 2 * $event->getPlayer()->getInventory()->getItemInHand()->getEnchantment(EnchantmentsManager::getEnchantment(CustomEnchantIds::PERFECTSOLITUDE))->getLevel();
            }

            if(isset($solitudeLevel) && mt_rand(0, 100) <= $level * 3 * $solitudeLevel) {
                $event->cancel();
            }
        };
    }
}