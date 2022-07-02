<?php

declare(strict_types = 1);

namespace vale\sage\demonic\enchants\enchantments\type\soul;

use pocketmine\entity\effect\Effect;
use pocketmine\event\entity\ProjectileHitEntityEvent;
use pocketmine\event\player\PlayerEvent;
use pocketmine\item\enchantment\ItemFlags;
use pocketmine\player\Player;
use pocketmine\scheduler\ClosureTask;
use vale\sage\demonic\enchants\CustomEnchant;
use vale\sage\demonic\enchants\CustomEnchantIds;
use vale\sage\demonic\enchants\enchantments\EnchantmentsManager;
use vale\sage\demonic\enchants\utils\SoulPoint;
use vale\sage\demonic\Loader;

class Teleblock extends CustomEnchant {

    /** @var array */
    public static array $teleblocked = [];

    public function __construct() {
        parent::__construct(
            "Teleblock",
            CustomEnchantIds::TELEBLOCK,
            "Active soul enchant. Your bow is enchanted with enderpearl blocking magic, damaged players will be unable to use enderpearls for up to 20 seconds, and will lose up to 15 enderpearls from their inv",
            5,
            ItemFlags::BOW,
            self::SOUL,
            self::OFFENSIVE,
            self::PROJECTILE_ENTITY,
            self::BOW_2
        );

        $this->callable = function (ProjectileHitEntityEvent $event, int $level) : void {
            $owner = $event->getEntity()->getOwningEntity();
            $hit = $event->getEntityHit();
            $has = false;

            if(!$hit instanceof Player || !$owner instanceof Player || in_array($hit->getUniqueId()->toString(), self::$teleblocked))  return;
            if(EnchantmentsManager::isSoulTrapped($owner)) return;

            $content = $owner->getInventory()->getItemInHand();

            if(SoulPoint::hasTracker($content)) {
                if(SoulPoint::getSoul($content) >= ($level * 6)) {
                    $owner->getInventory()->setItem($owner->getInventory()->getHeldItemIndex(), SoulPoint::setSoul($content, SoulPoint::getSoul($content) - ($level * 6)));
                    $has = true;
                }
            }
            
            if($has) {
                self::$teleblocked[] = $hit->getUniqueId()->toString();

                Loader::getInstance()->getScheduler()->scheduleDelayedTask(new ClosureTask(function () use($level, $hit) {
                    unset(self::$teleblocked[array_search($hit->getUniqueId()->toString(), self::$teleblocked)]);
                }), $level * 4 * 20);
            }
        };
    }


}