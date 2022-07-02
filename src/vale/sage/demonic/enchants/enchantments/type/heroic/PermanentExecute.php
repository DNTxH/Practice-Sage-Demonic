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

class PermanentExecute extends HeroicCustomEnchant {

    public function __construct() {
        parent::__construct(
            "Permanent Execute",
            CustomEnchantIds::PERMANENTEXECUTE,
            "Amplified Version of Execute, deal even more bonus damage when you target is <6 HP.",
            7,
            ItemFlags::SWORD,
            self::HEROIC,
            self::OFFENSIVE,
            self::ENTITY_DAMAGE_BY_ENTITY,
            self::SWORD,
            CustomEnchantIds::EXECUTE
        );

        $this->callable = function (EntityDamageByEntityEvent $event, int $level) : void {
            $entity = $event->getEntity();
            $damager = $event->getDamager();

            if($event->isCancelled() || !$entity instanceof Player || !$damager instanceof Player) return;

            if($level > 6) {
                $amp = 5;
            } else {
                $amp = $level;
            }

            if($entity->getHealth() <= 7) {
                if(!$damager->getEffects()->has(VanillaEffects::STRENGTH())) $damager->getEffects()->add(new EffectInstance(VanillaEffects::STRENGTH(), $level * 15, $amp));
            }
        };
    }

}