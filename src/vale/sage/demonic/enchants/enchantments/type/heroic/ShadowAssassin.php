<?php

declare(strict_types = 1);

namespace vale\sage\demonic\enchants\enchantments\type\heroic;

use pocketmine\entity\effect\Effect;
use pocketmine\event\entity\EntityDamageByEntityEvent;
use pocketmine\item\enchantment\ItemFlags;
use pocketmine\player\Player;
use vale\sage\demonic\enchants\CustomEnchantIds;

class ShadowAssassin extends HeroicCustomEnchant {

    public function __construct() {
        parent::__construct(
            "Shadow Assasin",
            CustomEnchantIds::SHADOWASSASIN,
            "The closer you are to your enemy, the more damage you deal (up to 1.875x). However, if you are more than 2 blocks away, you will deal LESS damage than normal. Requires Assassin V enchant on item to apply.",
            5,
            ItemFlags::SWORD,
            self::HEROIC,
            self::OFFENSIVE,
            self::ENTITY_DAMAGE_BY_ENTITY,
            self::SWORD,
            CustomEnchantIds::ASSASSIN
        );

        $this->callable = function (EntityDamageByEntityEvent $event, int $level) : void {
            $entity = $event->getEntity();
            $damager = $event->getDamager();

            if($event->isCancelled() || !$entity instanceof Player || !$damager instanceof Player) return;

            if($entity->getPosition()->distance($damager->getPosition()) <= 2.0) {
                $multi = ($level * 0.175) + 1;
                $event->setBaseDamage($event->getBaseDamage() * $multi);
            } else {
                $multi = 1 - ($level * 0.175);
                $event->setBaseDamage($event->getBaseDamage() * $multi);
            }
        };
    }

}