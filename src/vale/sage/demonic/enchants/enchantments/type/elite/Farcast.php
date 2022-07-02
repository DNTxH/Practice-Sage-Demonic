<?php

declare(strict_types = 1);

namespace vale\sage\demonic\enchants\enchantments\type\elite;

use pocketmine\entity\effect\Effect;
use pocketmine\event\entity\EntityDamageByEntityEvent;
use pocketmine\item\Bow;
use pocketmine\item\enchantment\ItemFlags;
use pocketmine\player\Player;
use vale\sage\demonic\enchants\CustomEnchant;
use vale\sage\demonic\enchants\CustomEnchantIds;

class Farcast extends CustomEnchant {

    public function __construct() {
        parent::__construct(
            "Farcast",
            CustomEnchantIds::FARCAST,
            "Chance to knockback melee attackers by a couple of blocks when they hit you. The lower your health, the higher the chance to proc.",
            5,
            ItemFlags::BOW,
            self::ELITE,
            self::DEFENSIVE,
            self::ENTITY_DAMAGE_BY_ENTITY,
            self::BOW_2
        );

        $this->callable = function (EntityDamageByEntityEvent $event, int $level) : void {
            $entity = $event->getEntity();
            $damager = $event->getDamager();

            if($event->isCancelled() || !$entity instanceof Player || !$damager instanceof Player) return;

            if($entity->getInventory()->getItemInHand() instanceof Bow) {
                if($entity->getHealth() <= $entity->getMaxHealth()) {
                    $chance = 3;
                } elseif($entity->getHealth() <= ($entity->getMaxHealth() * 0.8)) {
                    $chance = 6;
                } elseif($entity->getHealth() <= ($entity->getMaxHealth() * 0.6)) {
                    $chance = 9;
                } elseif($entity->getHealth() <= ($entity->getMaxHealth() * 0.4)) {
                    $chance = 12;
                } else {
                    $chance = 15;
                }

                if(mt_rand(0, 100) <= $chance) {
                    if($level === 1) $level = 2;
                    $damager->knockBack($level, $level);
                }
            }
        };
    }

}