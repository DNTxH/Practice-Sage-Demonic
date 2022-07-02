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
use vale\sage\demonic\enchants\event\SoulTrapEvent;
use vale\sage\demonic\enchants\utils\SoulPoint;
use vale\sage\demonic\Loader;

class SoulTrap extends CustomEnchant {

    /** @var array */
    public static array $soulTrapped = [];

    public function __construct() {
        parent::__construct(
            "Soul Trap",
            CustomEnchantIds::SOULTRAP,
            "Active soul enchant. Your axe is imbued with sealing magic, and has a chance to disable/negate all soul enchantments of your enemies on hit for (level x 4) seconds",
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

            if(in_array($entity->getUniqueId()->toString(), self::$soulTrapped) || in_array($damager->getUniqueId()->toString(), self::$soulTrapped)) return;

            $content = $entity->getInventory()->getItemInHand();

            if(SoulPoint::hasTracker($content)) {
                if(SoulPoint::getSoul($content) >= 50) {
                    $ev = new SoulTrapEvent($entity);
                    $ev->call();

                    if($ev->isCancelled()) return;

                    $entity->getInventory()->setItem($entity->getInventory()->getHeldItemIndex(), SoulPoint::setSoul($content, SoulPoint::getSoul($content) - 50));
                    $has = true;
                }
            }

            if($has) {
                self::$soulTrapped[] = $entity->getUniqueId()->toString();

                Loader::getInstance()->getScheduler()->scheduleDelayedTask(new ClosureTask(function () use($entity) {
                    unset(self::$soulTrapped[array_search($entity->getUniqueId()->toString(), self::$soulTrapped)]);
                }), $level * 20 * 4);
            }
        };
    }

}