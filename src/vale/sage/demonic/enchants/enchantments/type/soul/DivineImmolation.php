<?php

declare(strict_types=1);

namespace vale\sage\demonic\enchants\enchantments\type\soul;

use pocketmine\block\VanillaBlocks;
use pocketmine\entity\effect\Effect;
use pocketmine\event\entity\EntityDamageByEntityEvent;
use pocketmine\item\enchantment\ItemFlags;
use pocketmine\player\Player;
use pocketmine\world\particle\BlockBreakParticle;
use vale\sage\demonic\enchants\CustomEnchant;
use vale\sage\demonic\enchants\CustomEnchantIds;
use vale\sage\demonic\enchants\enchantments\EnchantmentsManager;
use vale\sage\demonic\enchants\utils\SoulPoint;

class DivineImmolation extends CustomEnchant {

    public function __construct() {
        parent::__construct(
            "Divine Immolation",
            CustomEnchantIds::DIVINEIMMOLATION,
            "Active soul enchant. Your weapons are imbued with divine fire, turning all your physical attacks into Area of Effect spells and igniting divine fire upon all nearby enemies.",
            4,
            ItemFlags::SWORD,
            self::SOUL,
            self::OFFENSIVE,
            self::ENTITY_DAMAGE_BY_ENTITY,
            self::SWORD
        );

        $this->callable = function (EntityDamageByEntityEvent $event, int $level) : void {
            $entity = $event->getEntity();
            $damager = $event->getDamager();
            $has = false;

            if($event->isCancelled() || !$entity instanceof Player || !$damager instanceof Player) return;
            if(EnchantmentsManager::isSoulTrapped($damager)) return;

            $content = $damager->getInventory()->getItemInHand();

            if(SoulPoint::hasTracker($content)) {
                if(SoulPoint::getSoul($content) >= 200) {
                    $damager->getInventory()->setItem($damager->getInventory()->getHeldItemIndex(), SoulPoint::setSoul($content, SoulPoint::getSoul($content) - 200));
                    $has = true;
                }
            }

            if($has) {
                foreach($damager->getWorld()->getNearbyEntities($damager->getBoundingBox()->expandedCopy($level * 3, $level * 3, $level * 3)) as $e) {
                    if(!$e instanceof Player) return;
                    if(!$e->isOnFire()) $e->setOnFire($level * 5);
                    $e->getWorld()->addParticle($e->getPosition(), new BlockBreakParticle(VanillaBlocks::PURPUR_PILLAR()));
                }
                // ignore ce influence
                $event->setBaseDamage($event->getFinalDamage() * (($level * 0.25) + 1));
                $damager->getWorld()->addParticle($damager->getPosition(), new BlockBreakParticle(VanillaBlocks::PURPUR_PILLAR()));
            }
        };
    }

}