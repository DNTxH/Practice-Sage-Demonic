<?php

declare(strict_types = 1);

namespace vale\sage\demonic\enchants\enchantments\type\legendary;

use pocketmine\entity\effect\Effect;
use pocketmine\event\entity\EntityDamageByEntityEvent;
use pocketmine\item\enchantment\ItemFlags;
use pocketmine\item\ItemIds;
use pocketmine\player\Player;
use vale\sage\demonic\enchants\CustomEnchant;
use vale\sage\demonic\enchants\CustomEnchantIds;
use vale\sage\demonic\enchants\event\PlayerDisarmorEvent;

class Disarmor extends CustomEnchant {

    public function __construct() {
        parent::__construct(
            "Disarmor",
            CustomEnchantIds::DISARMOR,
            "A slight chance of removing one piece of armor from your enemy when they are at low health.",
            8,
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

            if(mt_rand(0, 100) <= $level * 1.5) {
                $event = new PlayerDisarmorEvent($entity);

                $event->call();

                if($event->isCancelled()) return;

                $slots = [0, 1, 2, 3];
                $selection = $slots[array_rand($slots)];

                if($entity->getArmorInventory()->getItem($selection) !== null && $entity->getArmorInventory()->getItem($selection)->getId() !== ItemIds::AIR) {
                    $entity->getLocation()->getWorld()->dropItem($entity->getLocation(), $entity->getArmorInventory()->getItem($selection));
                    $entity->getArmorInventory()->setItem($selection, null);
                }
            }
        };
    }

}