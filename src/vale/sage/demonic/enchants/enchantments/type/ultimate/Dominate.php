<?php

declare(strict_types = 1);

namespace vale\sage\demonic\enchants\enchantments\type\ultimate;

use pocketmine\entity\effect\Effect;
use pocketmine\event\entity\EntityDamageByEntityEvent;
use pocketmine\event\Listener;
use pocketmine\item\enchantment\ItemFlags;
use pocketmine\player\Player;
use pocketmine\scheduler\ClosureTask;
use vale\sage\demonic\enchants\CustomEnchant;
use vale\sage\demonic\enchants\CustomEnchantIds;
use vale\sage\demonic\Loader;

class Dominate extends CustomEnchant implements Listener {

    /** @var array */
    private static $weakened = [];

    /** @var array */
    private static $weakenedLevels = [];

    public function __construct() {
        Loader::getInstance()->getServer()->getPluginManager()->registerEvents($this, Loader::getInstance());

        parent::__construct(
            "Dominate",
            CustomEnchantIds::DOMINATE,
            "Chance to weaken enemy players on hit, causing them to deal (level x 5%) less damage for (level x 2) seconds.",
            4,
            ItemFlags::SWORD,
            self::ULTIMATE,
            self::OFFENSIVE,
            self::ENTITY_DAMAGE_BY_ENTITY,
            self::SWORD
        );

        $this->callable = function (EntityDamageByEntityEvent $event, int $level) : void {
            $entity = $event->getEntity();
            $damager = $event->getDamager();

            if($event->isCancelled() || !$entity instanceof Player || !$damager instanceof Player || in_array($entity->getUniqueId()->toString(), self::$weakened)) return;

            if(mt_rand(0, 100) <= $level * 3) {
                self::$weakened[] = $entity->getUniqueId()->toString();
                self::$weakenedLevels[$entity->getUniqueId()->toString()] = $level;

                Loader::getInstance()->getScheduler()->scheduleDelayedTask(new ClosureTask(function () use($entity) : void {
                    unset(self::$weakened[array_search($entity->getUniqueId()->toString(), self::$weakened)]);
                    unset(self::$weakenedLevels[$entity->getUniqueId()->toString()]);
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

        if($event->isCancelled() || !$entity instanceof Player || !$damager instanceof Player || !in_array($damager->getUniqueId()->toString(), self::$weakened)) return;

        $event->setBaseDamage($event->getBaseDamage() * (1 - (self::$weakenedLevels[$damager->getUniqueId()->toString()] * 0.05)));
    }
}