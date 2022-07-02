<?php

declare(strict_types = 1);

namespace vale\sage\demonic\enchants\enchantments\type\soul;

use pocketmine\entity\effect\Effect;
use pocketmine\event\entity\EntityDamageByEntityEvent;
use pocketmine\item\enchantment\ItemFlags;
use pocketmine\player\Player;
use pocketmine\scheduler\ClosureTask;
use pocketmine\utils\TextFormat;
use vale\sage\demonic\enchants\CustomEnchant;
use vale\sage\demonic\enchants\CustomEnchantIds;
use vale\sage\demonic\enchants\utils\IndicatorManager;
use vale\sage\demonic\enchants\utils\SoulPoint;
use vale\sage\demonic\Loader;

class NaturesWrath extends CustomEnchant {

    public function __construct() {
        parent::__construct(
            "Natures Wrath",
            CustomEnchantIds::NATURESWRATH,
            "Passive Soul Enchantment. Temporarily freeze all enemies in a massive area around you, pushing them back and dealing massive nature damage.",
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

            if(in_array($entity->getUniqueId()->toString(), SoulTrap::$soulTrapped)) return;

            $content = $entity->getInventory()->getItemInHand();

            if(SoulPoint::hasTracker($content)) {
                if(SoulPoint::getSoul($content) >= 75) {
                    $entity->getInventory()->setItem($entity->getInventory()->getHeldItemIndex(), SoulPoint::setSoul($content, SoulPoint::getSoul($content) - 75));
                    $has = true;
                }
            }

            if($has) {
                foreach($entity->getWorld()->getNearbyEntities($entity->getBoundingBox()->expandedCopy($level * 5, $level * 5, $level * 5)) as $e) {
                    if(!$e instanceof Player) continue;
                    if($e->isImmobile()) continue;

                    $e->knockback($level * 5, $level * 5);
                    $e->setImmobile(true);
                    $e->setHealth($e->getHealth() - ((1 + $level) * $event->getFinalDamage()));

                    IndicatorManager::addTag($damager, $e, "*NATURES WRATH*", $level * 3, TextFormat::GREEN);
                    Loader::getInstance()->getScheduler()->scheduleDelayedTask(new ClosureTask(function () use($e) : void {
                        $e->setImmobile(false);
                    }), $level * 3 * 20);
                }

            }
        };
    }

}