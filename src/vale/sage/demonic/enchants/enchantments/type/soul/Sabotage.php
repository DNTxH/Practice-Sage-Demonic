<?php

declare(strict_types = 1);

namespace vale\sage\demonic\enchants\enchantments\type\soul;

use pocketmine\entity\effect\Effect;
use pocketmine\event\entity\EntityDamageByEntityEvent;
use pocketmine\item\enchantment\ItemFlags;
use pocketmine\player\Player;
use pocketmine\scheduler\ClosureTask;
use vale\sage\demonic\enchants\CustomEnchant;
use vale\sage\demonic\enchants\CustomEnchantIds;
use vale\sage\demonic\enchants\enchantments\EnchantmentsManager;
use vale\sage\demonic\enchants\utils\SoulPoint;
use vale\sage\demonic\Loader;

class Sabotage extends CustomEnchant {

    /** @var array */
    public static array $sabotaged = [];

    public function __construct() {
        parent::__construct(
            "Sabotage",
            CustomEnchantIds::SABOTAGE,
            "An active soul enchantment that gives a change to block an enemy players Rocket Escape and Guided Rocket Escape from activating for the next (level * 2) seconds",
            5,
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
            if(EnchantmentsManager::isSoulTrapped($entity)) return;
            if(in_array($entity->getUniqueId()->toString(), self::$sabotaged)) return;

            $content = $entity->getInventory()->getItemInHand();

            if(mt_rand(0, 100) <= $level * 4 && SoulPoint::hasTracker($content)) {
                if(SoulPoint::getSoul($content) >= 50) {
                    $entity->getInventory()->setItem($entity->getInventory()->getHeldItemIndex(), SoulPoint::setSoul($content, SoulPoint::getSoul($content) - 50));
                    $has = true;
                }
            }

            if($has) {
                self::$sabotaged[] = $entity->getUniqueId()->toString();

                Loader::getInstance()->getScheduler()->scheduleDelayedTask(new ClosureTask(function () use($entity) : void {
                    unset(self::$sabotaged[array_search($entity->getUniqueId()->toString(), self::$sabotaged)]);
                }), $level * 20 * 2);
            }
        };
    }

}