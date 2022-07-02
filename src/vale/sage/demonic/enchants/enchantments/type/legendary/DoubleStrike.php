<?php

declare(strict_types = 1);

namespace vale\sage\demonic\enchants\enchantments\type\legendary;

use pocketmine\entity\effect\Effect;
use pocketmine\event\entity\EntityDamageByEntityEvent;
use pocketmine\item\enchantment\ItemFlags;
use pocketmine\player\Player;
use vale\sage\demonic\enchants\CustomEnchant;
use vale\sage\demonic\enchants\CustomEnchantIds;

class DoubleStrike extends CustomEnchant {

    public function __construct() {
        parent::__construct(
            "Double Strike",
            CustomEnchantIds::DOUBLESTRIKE,
            "A chance to attack twice in one swing. All your enchantments can re-proc on this second attack, and it occurs instantly.",
            3,
            ItemFlags::SWORD,
            self::LEGENDARY,
            self::OFFENSIVE,
            self::ENTITY_DAMAGE_BY_ENTITY,
            self::SWORD
        );

        $this->callable = function (EntityDamageByEntityEvent $event, int $level) : void {
            $entity = $event->getEntity();
            $damager = $event->getDamager();

            if($event->isCancelled() || !$entity instanceof Player || !$damager instanceof Player) return;

            if(mt_rand(0, 100) <= ($level * 3)) {
                $event = new EntityDamageByEntityEvent($damager, $entity, $event->getCause(), $event->getBaseDamage(), $event->getModifiers(), $event->getKnockBack());
                $event->call();
            }
        };
    }

}