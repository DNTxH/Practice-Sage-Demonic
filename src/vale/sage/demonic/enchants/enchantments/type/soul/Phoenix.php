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

class Phoenix extends CustomEnchant {

    /** @var array */
    private static array $cooldowns = [];

    public function __construct() {
        parent::__construct(
            "Phoenix",
            CustomEnchantIds::PHOENIX,
            "An attack that would normally kill you will instead heal you to full HP. Can only be activated once every couple minutes.",
            3,
            ItemFlags::FEET,
            self::SOUL,
            self::DEFENSIVE,
            self::ENTITY_DAMAGE_BY_ENTITY,
            self::BOOTS
        );

        $this->callable = function (EntityDamageByEntityEvent $event, int $level) : void {
            $entity = $event->getEntity();
            $damager = $event->getDamager();
            $has = false;

            if($event->isCancelled() || !$entity instanceof Player || !$damager instanceof Player) return;

            if(in_array($entity->getUniqueId()->toString(), self::$cooldowns)) return;
            if(EnchantmentsManager::isSoulTrapped($entity)) return;

            if($entity->getHealth() - $event->getFinalDamage() <= 0.0) {

                $content = $entity->getInventory()->getItemInHand();

                if(SoulPoint::hasTracker($content)) {
                    if(SoulPoint::getSoul($content) >= 500) {
                        $entity->getInventory()->setItem($entity->getInventory()->getHeldItemIndex(), SoulPoint::setSoul($content, SoulPoint::getSoul($content) - 500));
                        $has = true;
                    }
                }

                if($has) {
                    $event->cancel();
                    $entity->setHealth($entity->getMaxHealth());
                    self::$cooldowns[] = $entity->getUniqueId()->toString();

                    Loader::getInstance()->getScheduler()->scheduleDelayedTask(new ClosureTask(function () use($entity) {
                        unset(self::$cooldowns[array_search($entity->getUniqueId()->toString(), self::$cooldowns)]);
                    }), (120 * 20) - ((60 / $level) * 20));
                }
            }
        };
    }

}