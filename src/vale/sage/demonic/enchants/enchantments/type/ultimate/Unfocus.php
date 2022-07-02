<?php

declare(strict_types = 1);

namespace vale\sage\demonic\enchants\enchantments\type\ultimate;

use pocketmine\entity\effect\Effect;
use pocketmine\event\entity\EntityDamageByEntityEvent;
use pocketmine\event\entity\ProjectileHitEntityEvent;
use pocketmine\event\Listener;
use pocketmine\item\enchantment\ItemFlags;
use pocketmine\player\Player;
use pocketmine\scheduler\ClosureTask;
use vale\sage\demonic\enchants\CustomEnchant;
use vale\sage\demonic\enchants\CustomEnchantIds;
use vale\sage\demonic\Loader;

class Unfocus extends CustomEnchant implements Listener {

    /** @var array */
    private static $unfocused = [];

    /** @var array */
    private static $unfocusedLevels = [];

    public function __construct() {
        Loader::getInstance()->getServer()->getPluginManager()->registerEvents($this, Loader::getInstance());

        parent::__construct(
            "Unfocuse",
            CustomEnchantIds::UNFOCUS,
            "Chance to Unfocus target player, reducing their outgoing bow damage by 50% for up to 10 seconds.",
            5,
            ItemFlags::BOW,
            self::ULTIMATE,
            self::OFFENSIVE,
            self::PROJECTILE_ENTITY,
            self::BOW_2
        );

        $this->callable = function (ProjectileHitEntityEvent $event, int $level) : void {
            $owner = $event->getEntity()->getOwningEntity();
            $hit = $event->getEntityHit();

            if(!$owner instanceof Player || !$hit instanceof Player) return;

            if(in_array($hit->getUniqueId()->toString(), self::$unfocused) && mt_rand(0, 100) <= $level * 2) {
                self::$unfocused[] = $hit->getUniqueId()->toString();
                self::$unfocusedLevels[$hit->getUniqueId()->toString()] = $level;

                Loader::getInstance()->getScheduler()->scheduleDelayedTask(new ClosureTask(function () use($hit): void {
                    unset(self::$unfocused[array_search($hit->getUniqueId()->toString(), self::$unfocused)]);
                    unset(self::$unfocusedLevels[$hit->getUniqueId()->toString()]);
                }), $level * 2 * 20);
            }
        };
    }

    /**
     * @param EntityDamageByEntityEvent $event
     * @return void
     */
    public function onDamage(EntityDamageByEntityEvent $event) : void {
        $entity = $event->getEntity();
        $damager = $event->getDamager();

        if($event->isCancelled() || !$entity instanceof Player || !$damager instanceof Player || !in_array($damager->getUniqueId()->toString(), self::$unfocused)) return;

        if($event->getCause() === EntityDamageByEntityEvent::CAUSE_PROJECTILE) {
            $event->setBaseDamage($event->getBaseDamage() * (1 - (0.1 * self::$unfocusedLevels[$damager->getUniqueId()->toString()])));
        }
    }
}