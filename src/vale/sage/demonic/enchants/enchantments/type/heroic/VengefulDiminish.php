<?php

declare(strict_types = 1);

namespace vale\sage\demonic\enchants\enchantments\type\heroic;

use pocketmine\entity\effect\Effect;
use pocketmine\event\entity\EntityDamageByEntityEvent;
use pocketmine\item\enchantment\ItemFlags;
use pocketmine\player\Player;
use vale\sage\demonic\enchants\CustomEnchantIds;

class VengefulDiminish extends HeroicCustomEnchant {

    /** @var array */
    private static array $damages = [];

    public function __construct() {
        parent::__construct(
            "Vengeful Diminish",
            CustomEnchantIds::VENGEFULDIMINISH,
            "When this effect procs, the next attack dealt to you cannot deal more than the (took amount of damage / 2) you took from the previous attack, any damage above this limit will be dealt to the attacker. (i.e. if you're damaged for 2HP, the next attack cannot deal more than 1HP of damage, while the attacker is damaged by 1HP) Requires Diminish VI enchant on item to apply. Cannot be black-scrolled.",
            6,
            ItemFlags::TORSO,
            self::HEROIC,
            self::DEFENSIVE,
            self::ENTITY_DAMAGE_BY_ENTITY,
            self::CHESTPLATE,
            CustomEnchantIds::DIMINISH
        );

        $this->callable = function (EntityDamageByEntityEvent $event, int $level) : void {
            $entity = $event->getEntity();
            $damager = $event->getDamager();

            if($event->isCancelled() || !$entity instanceof Player || !$damager instanceof Player) return;

            if(mt_rand(0, 100) <= $level * 3) {
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
            $damager->setHealth($damager->getHealth() - ($event->getFinalDamage() - (self::$damages[$entity->getUniqueId()->toString()] / 2)));
        }

        unset(self::$damages[$entity->getUniqueId()->toString()]);
    }
}