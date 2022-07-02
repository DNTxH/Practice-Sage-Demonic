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
use vale\sage\demonic\enchants\event\MetaphysicalEvent;

class Trap extends CustomEnchant {

    public function __construct() {
        parent::__construct(
            "Trap",
            CustomEnchantIds::TRAP,
            "A chance to give a buffed slowness effect.",
            3,
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

            if(mt_rand(0, 100) <= $level * 2) {
                $ev = new MetaphysicalEvent($entity);

                $ev->call();

                if($ev->isCancelled()) return;

                $entity->getEffects()->add(new EffectInstance(VanillaEffects::SLOWNESS(), $level * 20, $level * 2));
            }
        };
    }

}