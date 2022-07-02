<?php

declare(strict_types = 1);

namespace vale\sage\demonic\enchants\enchantments\type\legendary;

use pocketmine\entity\effect\Effect;
use pocketmine\event\entity\EntityDamageByEntityEvent;
use pocketmine\event\Listener;
use pocketmine\item\enchantment\ItemFlags;
use pocketmine\player\Player;
use vale\sage\demonic\enchants\CustomEnchant;
use vale\sage\demonic\enchants\CustomEnchantIds;
use vale\sage\demonic\Loader;

class Diminish extends CustomEnchant implements Listener {

    /** @var array */
    private static array $damages = [];

    public function __construct() {
        Loader::getInstance()->getServer()->getPluginManager()->registerEvents($this, Loader::getInstance());

        parent::__construct(
            "Diminish",
            CustomEnchantIds::DIMINISH,
            "When this effect procs, the next attack dealt to you cannot deal more than the (total amount of damage / 2) you took from the previous attack. (i.e. if you're damaged for 2HP, the next attack cannot deal more than 1HP of damage.)",
            6,
            ItemFlags::TORSO,
            self::LEGENDARY,
            self::DEFENSIVE,
            self::ENTITY_DAMAGE_BY_ENTITY,
            self::CHESTPLATE
        );

        $this->callable = function (EntityDamageByEntityEvent $event, int $level) : void {
            $entity = $event->getEntity();
            $damager = $event->getDamager();

            if($event->isCancelled() || !$entity instanceof Player || !$damager instanceof Player) return;

            if(mt_rand(0, 100) <= $level * 2) {
                self::$damages[$entity->getUniqueId()->toString()] = $event->getFinalDamage();
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

        if(!isset(self::$damages[$entity->getUniqueId()->toString()])) return;

        if($event->getFinalDamage() > self::$damages[$entity->getUniqueId()->toString()] / 2) {
            $event->setBaseDamage($event->getBaseDamage() / 2);
        }

        unset(self::$damages[$entity->getUniqueId()->toString()]);
    }
}