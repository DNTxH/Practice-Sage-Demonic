<?php

declare(strict_types = 1);

namespace vale\sage\demonic\enchants\enchantments\type\heroic;

use pocketmine\entity\effect\Effect;
use pocketmine\event\entity\EntityDamageByEntityEvent;
use pocketmine\item\Axe;
use pocketmine\item\enchantment\ItemFlags;
use pocketmine\player\Player;
use vale\sage\demonic\enchants\CustomEnchantIds;

class ExtremeInsanity extends HeroicCustomEnchant {

    public function __construct() {
        parent::__construct(
            "Extreme Insanity",
            CustomEnchantIds::EXTREMEINSANITY,
            "You swing your sword like an extreme maniac. Multiples damage against players that are wielding a SWORD at the time they are hit.",
            8,
            ItemFlags::AXE,
            self::HEROIC,
            self::OFFENSIVE,
            self::ENTITY_DAMAGE_BY_ENTITY,
            self::SWORD,
            CustomEnchantIds::EXTREMEINSANITY
        );

        $this->callable = function (EntityDamageByEntityEvent $event, int $level) : void {
            $entity = $event->getEntity();
            $damager = $event->getDamager();

            if($event->isCancelled() || !$entity instanceof Player || !$damager instanceof Player) return;

            if($entity->getInventory()->getItemInHand() instanceof Axe) {
                $event->setBaseDamage($event->getBaseDamage() * ((0.075 * $level) + 1));
            }
        };
    }

}