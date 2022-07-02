<?php

declare(strict_types = 1);

namespace vale\sage\demonic\enchants\enchantments\type\elite;

use pocketmine\event\entity\EntityDamageByEntityEvent;
use pocketmine\item\Durable;
use pocketmine\item\enchantment\ItemFlags;
use pocketmine\player\Player;
use vale\sage\demonic\enchants\CustomEnchant;
use vale\sage\demonic\enchants\CustomEnchantIds;

class Demonforged extends CustomEnchant {

    public function __construct() {
        parent::__construct(
            "Demonforged",
            CustomEnchantIds::DEMONFORGED,
            "Increases durability loss on your enemy's armor.",
            4,
            ItemFlags::SWORD,
            self::ELITE,
            self::OFFENSIVE,
            self::ENTITY_DAMAGE_BY_ENTITY,
            self::SWORD
        );

        $this->callable = function (EntityDamageByEntityEvent $event, int $level) : void {
            $entity = $event->getEntity();
            $damager = $event->getDamager();

            if($event->isCancelled() || !$entity instanceof Player || !$damager instanceof Player) return;

            if($level > 2) {
                $damage = 2;
            } else {
                $damage = 1;
            }

            foreach ($entity->getArmorInventory()->getContents() as $content) {
                if($content instanceof Durable) {
                    $content->applyDamage($damage);
                }
            }
        };
    }

}