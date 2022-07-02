<?php

declare(strict_types = 1);

namespace vale\sage\demonic\enchants\enchantments\type\heroic;

use pocketmine\entity\effect\Effect;
use pocketmine\entity\effect\EffectInstance;
use pocketmine\entity\effect\VanillaEffects;
use pocketmine\event\entity\EntityDamageByEntityEvent;
use pocketmine\item\enchantment\ItemFlags;
use pocketmine\player\Player;
use vale\sage\demonic\enchants\CustomEnchantIds;

class TitanTrap extends HeroicCustomEnchant {

    public function __construct() {
        parent::__construct(
            "Titan Trap",
            CustomEnchantIds::TITANTRAP,
            "A chance to give a longer lasting buffed slowness effect.",
            3,
            ItemFlags::SWORD,
            self::HEROIC,
            self::OFFENSIVE,
            self::ENTITY_DAMAGE_BY_ENTITY,
            self::SWORD,
            CustomEnchantIds::TRAP
        );

        $this->callable = function (EntityDamageByEntityEvent $event, int $level) : void {
            $entity = $event->getEntity();
            $damager = $event->getDamager();

            if($event->isCancelled() || !$entity instanceof Player || !$damager instanceof Player) return;

            if(mt_rand(0, 100) <= $level * 2) {
                $entity->getEffects()->add(new EffectInstance(VanillaEffects::SLOWNESS(), $level * 20, $level * 3));
            }
        };
    }

}