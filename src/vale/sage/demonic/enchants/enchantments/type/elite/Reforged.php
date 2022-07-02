<?php

declare(strict_types = 1);

namespace vale\sage\demonic\enchants\enchantments\type\elite;

use pocketmine\entity\effect\Effect;
use pocketmine\event\entity\EntityDamageByEntityEvent;
use pocketmine\item\Durable;
use pocketmine\item\enchantment\ItemFlags;
use pocketmine\player\Player;
use vale\sage\demonic\enchants\CustomEnchant;
use vale\sage\demonic\enchants\CustomEnchantIds;

class Reforged extends CustomEnchant {

    public function __construct() {
        parent::__construct(
            "Reforged",
            CustomEnchantIds::REFORGED,
            "Protects armor durability, armor will take longer to break.",
            10,
            ItemFlags::ARMOR,
            self::ULTIMATE,
            self::DEFENSIVE,
            self::ENTITY_DAMAGE_BY_ENTITY,
            self::ARMOR
        );

        $this->callable = function (EntityDamageByEntityEvent $event, int $level) : void {
            $entity = $event->getEntity();
            $damager = $event->getDamager();

            if($event->isCancelled() || !$entity instanceof Player || !$damager instanceof Player) return;

            foreach ($entity->getArmorInventory()->getContents() as $content) {
                if($content instanceof Durable) $content->setDamage((int)($event->getFinalDamage() / 10) * $level);
            }
        };
    }

}