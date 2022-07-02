<?php

declare(strict_types = 1);

namespace vale\sage\demonic\enchants\enchantments\type\soul;

use pocketmine\event\entity\EntityDamageByEntityEvent;
use pocketmine\item\Durable;
use pocketmine\item\enchantment\ItemFlags;
use pocketmine\player\Player;
use vale\sage\demonic\enchants\CustomEnchant;
use vale\sage\demonic\enchants\CustomEnchantIds;
use vale\sage\demonic\enchants\enchantments\EnchantmentsManager;
use vale\sage\demonic\enchants\utils\SoulPoint;

class Immortal extends CustomEnchant {

    public function __construct() {
        parent::__construct(
            "Immortal",
            CustomEnchantIds::IMMORTAL,
            "Prevents your armor from taking durability damage in exchange for souls.",
            4,
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
                if(SoulPoint::getSoul($content) >= (20 / $level)) {
                    $entity->getInventory()->setItem($entity->getInventory()->getHeldItemIndex(), SoulPoint::setSoul($content, SoulPoint::getSoul($content) - (20 / $level)));
                    $has = true;
                }
            }

            if($has) {
                foreach ($entity->getArmorInventory()->getContents() as $armor) {
                    if($armor instanceof Durable) $armor->setDamage((int)$armor->getDamage() - $event->getFinalDamage());
                }
            }
        };
    }

}