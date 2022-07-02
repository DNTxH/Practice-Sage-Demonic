<?php

declare(strict_types = 1);

namespace vale\sage\demonic\enchants\enchantments\type\legendary;

use pocketmine\entity\effect\Effect;
use pocketmine\event\entity\EntityDamageByEntityEvent;
use pocketmine\item\Axe;
use pocketmine\item\enchantment\ItemFlags;
use pocketmine\player\Player;
use vale\sage\demonic\enchants\CustomEnchant;
use vale\sage\demonic\enchants\CustomEnchantIds;

class Insanity extends CustomEnchant {

    public function __construct() {
        parent::__construct(
            "Insanity",
            CustomEnchantIds::INSANITY,
            "You swing your axe like a maniac. Multiplies damage against players who are wielding a SWORD at the time they are hit.",
            8,
            ItemFlags::AXE,
            self::LEGENDARY,
            self::OFFENSIVE,
            self::ENTITY_DAMAGE_BY_ENTITY,
            self::AXE
        );

        $this->callable = function (EntityDamageByEntityEvent $event, int $level) : void {
            $entity = $event->getEntity();
            $damager = $event->getDamager();

            if($event->isCancelled() || !$entity instanceof Player || !$damager instanceof Player) return;

            if($entity->getInventory()->getItemInHand() instanceof Axe) {
                $event->setBaseDamage($event->getBaseDamage() * ((0.05 * $level) + 1));
            }
        };
    }

}