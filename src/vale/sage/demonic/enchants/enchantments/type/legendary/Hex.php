<?php

declare(strict_types = 1);

namespace vale\sage\demonic\enchants\enchantments\type\legendary;

use pocketmine\entity\effect\Effect;
use pocketmine\event\entity\EntityDamageByEntityEvent;
use pocketmine\event\Listener;
use pocketmine\item\enchantment\ItemFlags;
use pocketmine\player\Player;
use pocketmine\scheduler\ClosureTask;
use vale\sage\demonic\enchants\CustomEnchant;
use vale\sage\demonic\enchants\CustomEnchantIds;
use vale\sage\demonic\Loader;

class Hex extends CustomEnchant implements Listener {

    /** @var array */
    private static array $hexed = [];

    public function __construct() {
        Loader::getInstance()->getServer()->getPluginManager()->registerEvents($this, Loader::getInstance());

        parent::__construct(
            "Hex",
            CustomEnchantIds::HEX,
            "Once a target is affected by Hex, a portion of all their outgoing damage is reflected back onto them for up to 5 seconds.",
            5,
            ItemFlags::AXE,
            self::LEGENDARY,
            self::OFFENSIVE,
            self::ENTITY_DAMAGE_BY_ENTITY,
            self::AXE
        );

        $this->callable = function (EntityDamageByEntityEvent $event, int $level) : void {
            $entity = $event->getEntity();
            $damager = $event->getDamager();

            if($event->isCancelled() || !$entity instanceof Player || !$damager instanceof Player) return;

            if(mt_rand(0, 100) <= $level * 3 && !isset(self::$hexed[$entity->getUniqueId()->toString()])) {
                self::$hexed[$entity->getUniqueId()->toString()] = $level;

                Loader::getInstance()->getScheduler()->scheduleDelayedTask(new ClosureTask(function () use($entity) : void {
                    unset(self::$hexed[$entity->getUniqueId()->toString()]);
                }), $level * 20);
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

        if($event->isCancelled() || !$entity instanceof Player || !$damager instanceof Player) return;

        if(!isset(self::$hexed[$damager->getUniqueId()->toString()])) return;

        $damager->setHealth($damager->getHealth() - (self::$hexed[$damager->getUniqueId()->toString()] * ($event->getFinalDamage() / 15)));
    }
}