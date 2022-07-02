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

class FeatherWeight extends CustomEnchant {

    public function __construct() {
        parent::__construct(
            "Featherweight",
            CustomEnchantIds::FEATHERWEIGHT,
            "A chance to give a burst of haste",
            3,
            ItemFlags::SWORD,
            self::UNIQUE,
            self::OFFENSIVE,
            self::ENTITY_DAMAGE_BY_ENTITY,
            self::SWORD
        );

        $this->callable = function (EntityDamageByEntityEvent $event, int $level) : void {
            $damager = $event->getDamager();

            if(!$damager instanceof Player || $event->isCancelled()) return;

            if(mt_rand(0, 100) <= ($level * 2)) {
                if(!$damager->getEffects()->has(VanillaEffects::HASTE())) {
                    if($level === 1) $level = 2;
                    $damager->getEffects()->add(new EffectInstance(VanillaEffects::HASTE(), $level * 40, $level - 1, false));
                }
            }
        };
    }
}