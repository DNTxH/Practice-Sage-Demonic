<?php

declare(strict_types = 1);

namespace vale\sage\demonic\enchants\enchantments\type\heroic;

use pocketmine\entity\effect\Effect;
use pocketmine\event\entity\EntityDamageByEntityEvent;
use pocketmine\item\enchantment\ItemFlags;
use pocketmine\item\Sword;
use pocketmine\player\Player;
use vale\sage\demonic\enchants\CustomEnchantIds;

class PaladinArmored extends HeroicCustomEnchant {

    public function __construct() {
        parent::__construct(
            "Paladin Armored",
            CustomEnchantIds::PALADINARMORED,
            "The first stackable heroic enchantment. Negates 150% more enemy sword damage than normal Armored per level. A chance to be Blessed every time you are struck by an enemy sword",
            4,
            ItemFlags::ARMOR,
            self::HEROIC,
            self::DEFENSIVE,
            self::ENTITY_DAMAGE_BY_ENTITY,
            self::ARMOR,
            CustomEnchantIds::ARMORED
        );

        $this->callable = function (EntityDamageByEntityEvent $event, int $level) : void {
            $entity = $event->getEntity();
            $damager = $event->getDamager();

            if($event->isCancelled() || !$entity instanceof Player || !$damager instanceof Player) return;

            if($damager->getInventory()->getItemInHand() instanceof Sword) $event->setBaseDamage($event->getBaseDamage() * (1 - (0.0275 * $level)));

            if(mt_rand(0, 100) <= $level * 2.5) {
                foreach($entity->getEffects()->all() as $effect) {
                    if($effect->getType()->isBad()) $entity->getEffects()->remove($effect);
                }
            }
        };
    }

}