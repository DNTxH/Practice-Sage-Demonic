<?php

declare(strict_types = 1);

namespace vale\sage\demonic\enchants\enchantments\type\legendary;

use pocketmine\entity\effect\Effect;
use pocketmine\event\entity\EntityDamageByEntityEvent;
use pocketmine\item\Durable;
use pocketmine\item\enchantment\ItemFlags;
use pocketmine\player\Player;
use vale\sage\demonic\enchants\CustomEnchant;
use vale\sage\demonic\enchants\CustomEnchantIds;

class Blacksmith extends CustomEnchant {

    public function __construct() {
        parent::__construct(
            "Blacksmith",
            CustomEnchantIds::BLACKSMITH,
            "Chance to heal your most damaged piece of armor by 1-2 durability whenever you hit a player, but when it procs your attack will only deal 50% of the normal damage.",
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

            if(mt_rand(0, 100) <= $level * 2) {
                foreach ($damager->getArmorInventory()->getContents() as $content) {
                    if($content instanceof Durable) $content->setDamage($content->getDamage() - mt_rand(1, 2));
                }
                $event->setBaseDamage($event->getBaseDamage() / 2);
            }
        };
    }
}