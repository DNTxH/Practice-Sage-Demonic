<?php

declare(strict_types = 1);

namespace vale\sage\demonic\enchants\enchantments\type\soul;

use vale\sage\demonic\enchants\enchantments\EnchantmentsManager;
use vale\sage\demonic\enchants\utils\SoulPoint;
use pocketmine\event\entity\EntityDamageByEntityEvent;
use pocketmine\item\enchantment\ItemFlags;
use pocketmine\player\Player;
use vale\sage\demonic\enchants\CustomEnchant;
use vale\sage\demonic\enchants\CustomEnchantIds;

class Rogue extends CustomEnchant {

    public function __construct() {
        parent::__construct(
            "Rogue",
            CustomEnchantIds::ROGUE,
            "Deal up to 2.0x damage if hitting your enemy from behind.",
            3,
            ItemFlags::AXE,
            self::SOUL,
            self::OFFENSIVE,
            self::ENTITY_DAMAGE_BY_ENTITY,
            self::AXE
        );

        $this->callable = function (EntityDamageByEntityEvent $event, int $level) : void {
            $entity = $event->getEntity();
            $damager = $event->getDamager();
            $has = false;

            if($event->isCancelled() || !$entity instanceof Player || !$damager instanceof Player) return;

            if(EnchantmentsManager::isSoulTrapped($damager)) return;

            if($entity->getDirectionVector()->equals($damager->getDirectionVector()->multiply(-1))) {

                $content = $entity->getInventory()->getItemInHand();

                if(SoulPoint::hasTracker($content)) {
                    if(SoulPoint::getSoul($content) >= 100) {
                        $entity->getInventory()->setItem($entity->getInventory()->getHeldItemIndex(), SoulPoint::setSoul($content, SoulPoint::getSoul($content) - 100));
                        $has = true;
                    }
                }

                if($has) {
                    $event->setBaseDamage($event->getBaseDamage() * ((0.33 * $level) + 1));
                }
            }
        };
    }

}