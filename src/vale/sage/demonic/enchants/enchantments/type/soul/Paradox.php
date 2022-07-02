<?php

declare(strict_types = 1);

namespace vale\sage\demonic\enchants\enchantments\type\soul;

use pocketmine\entity\effect\Effect;
use pocketmine\event\entity\EntityDamageByEntityEvent;
use pocketmine\item\enchantment\ItemFlags;
use pocketmine\player\Player;
use vale\sage\demonic\enchants\CustomEnchant;
use vale\sage\demonic\enchants\CustomEnchantIds;
use vale\sage\demonic\enchants\enchantments\EnchantmentsManager;
use vale\sage\demonic\enchants\utils\SoulPoint;

class Paradox extends CustomEnchant {

    public function __construct() {
        parent::__construct(
            "Paradox",
            CustomEnchantIds::PARADOX,
            "Passive soul enchantment. Heals all nearby allies in a massive area around you for a portion of all damage dealt to you",
            5,
            ItemFlags::ARMOR,
            self::SOUL,
            self::DEFENSIVE,
            self::ENTITY_DAMAGE_BY_ENTITY,
            self::ARMOR
        );

        $this->callable = function (EntityDamageByEntityEvent $event, int $level) : void {
            $entity = $event->getEntity();
            $damager = $event->getDamager();
            $has = false;

            if($event->isCancelled() || !$entity instanceof Player || !$damager instanceof Player) return;
            if(EnchantmentsManager::isSoulTrapped($entity)) return;

            $content = $entity->getInventory()->getItemInHand();

            if(SoulPoint::hasTracker($content)) {
                if(SoulPoint::getSoul($content) >= 5) {
                    $entity->getInventory()->setItem($entity->getInventory()->getHeldItemIndex(), SoulPoint::setSoul($content, SoulPoint::getSoul($content) - 5));
                    $has = true;
                }
            }

            if($has) {
                foreach($entity->getWorld()->getNearbyEntities($entity->getBoundingBox()->expandedCopy($level * 10, $level * 10, $level * 10)) as $e) {
                    if(!$e instanceof Player) continue;
                    $e->setHealth($e->getHealth() + (($event->getFinalDamage() / 5) * $level));
                }
            }
        };
    }
}