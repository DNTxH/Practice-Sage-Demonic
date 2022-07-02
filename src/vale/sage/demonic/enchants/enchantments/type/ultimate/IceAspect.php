<?php

declare(strict_types = 1);

namespace vale\sage\demonic\enchants\enchantments\type\ultimate;

use pocketmine\block\VanillaBlocks;
use pocketmine\entity\effect\EffectInstance;
use pocketmine\entity\effect\VanillaEffects;
use pocketmine\event\entity\EntityDamageByEntityEvent;
use pocketmine\item\enchantment\ItemFlags;
use pocketmine\player\Player;
use vale\sage\demonic\enchants\CustomEnchant;
use vale\sage\demonic\enchants\CustomEnchantIds;
use vale\sage\demonic\enchants\event\MetaphysicalEvent;

class IceAspect extends CustomEnchant {

    public function __construct() {
        parent::__construct(
            "Ice Aspect",
            CustomEnchantIds::ICEASPECT,
            "A chance of causing the slowness effect on your enemy.",
            3,
            ItemFlags::SWORD,
            self::ULTIMATE,
            self::OFFENSIVE,
            self::ENTITY_DAMAGE_BY_ENTITY,
            self::SWORD
        );

        $this->callable = function (EntityDamageByEntityEvent $event, int $level) : void {
            $entity = $event->getEntity();
            $damager = $event->getDamager();

            if($event->isCancelled() || !$entity instanceof Player || !$damager instanceof Player) return;

            if(mt_rand(0, 100) <= $level * 2.5) {
                $ev = new MetaphysicalEvent($entity);

                $ev->call();

                if($ev->isCancelled()) return;

                if(!$entity->getEffects()->has(VanillaEffects::SLOWNESS())) $entity->getEffects()->add(new EffectInstance(VanillaEffects::SLOWNESS(), $level * 20, $level));
            }
        };
    }


}