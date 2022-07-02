<?php

declare(strict_types = 1);

namespace vale\sage\demonic\enchants\enchantments\type\heroic;

use pocketmine\entity\effect\Effect;
use pocketmine\event\entity\EntityDamageByEntityEvent;
use pocketmine\item\Durable;
use pocketmine\item\enchantment\ItemFlags;
use pocketmine\player\Player;
use vale\sage\demonic\enchants\CustomEnchantIds;

class MasterBlacksmith extends HeroicCustomEnchant {

    public function __construct() {
        parent::__construct(
            "Master Blacksmith",
            CustomEnchantIds::MASTERBLACKSMITH,
            "Chance to heal your most damaged piece of armor by 2-3 durability whenever you hit a player, but when it procs your attack will only deal 75-100% of the normal damage. Requires Blacksmith V enchant on item to apply.",
            5,
            ItemFlags::AXE,
            self::HEROIC,
            self::OFFENSIVE,
            self::ENTITY_DAMAGE_BY_ENTITY,
            self::AXE,
            CustomEnchantIds::BLACKSMITH
        );

        $this->callable = function (EntityDamageByEntityEvent $event, int $level) : void {
            $entity = $event->getEntity();
            $damager = $event->getDamager();

            if($event->isCancelled() || !$entity instanceof Player || !$damager instanceof Player) return;

            if(mt_rand(0, 100) <= $level * 2) {
                foreach ($damager->getArmorInventory()->getContents() as $content) {
                    if($content instanceof Durable) $content->setDamage($content->getDamage() - mt_rand(2, 3));
                }
                $event->setBaseDamage($event->getBaseDamage() * (mt_rand(75, 100) / 100));
            }
        };
    }
}