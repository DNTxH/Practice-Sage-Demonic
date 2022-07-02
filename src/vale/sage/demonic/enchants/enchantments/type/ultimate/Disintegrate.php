<?php

declare(strict_types = 1);

namespace vale\sage\demonic\enchants\enchantments\type\ultimate;

use pocketmine\entity\effect\Effect;
use pocketmine\entity\EntityDataHelper;
use pocketmine\event\entity\EntityDamageByEntityEvent;
use pocketmine\item\Durable;
use pocketmine\item\enchantment\ItemFlags;
use pocketmine\player\Player;
use vale\sage\demonic\enchants\CustomEnchant;
use vale\sage\demonic\enchants\CustomEnchantIds;

class Disintegrate extends CustomEnchant {

    public function __construct() {
        parent::__construct(
            "Disintegrate",
            CustomEnchantIds::DISINTEGRATE,
            "Chance to deal double durability damage to all enemy armor with every attack.",
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

            if($event->isCancelled() || !$entity instanceof Player || !$damager instanceof Player) return;

            foreach($entity->getArmorInventory()->getContents() as $content) {
                if($content instanceof Durable) $content->applyDamage((int)$event->getFinalDamage());
            }
        };
    }

}