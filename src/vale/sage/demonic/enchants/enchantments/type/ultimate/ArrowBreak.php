<?php

declare(strict_types = 1);

namespace vale\sage\demonic\enchants\enchantments\type\ultimate;

use pocketmine\entity\effect\Effect;
use pocketmine\event\entity\EntityDamageByEntityEvent;
use pocketmine\item\Axe;
use pocketmine\item\enchantment\ItemFlags;
use pocketmine\player\Player;
use vale\sage\demonic\enchants\CustomEnchant;
use vale\sage\demonic\enchants\CustomEnchantIds;

class ArrowBreak extends CustomEnchant {

    public function __construct() {
        parent::__construct(
            "Arrow Break",
            CustomEnchantIds::ARROWBREAK,
            "Chance for arrows to bounce off and do no damage to you whenever you are wielding an axe with this enchantment on it.",
            6,
            ItemFlags::AXE,
            self::ULTIMATE,
            self::DEFENSIVE,
            self::ENTITY_DAMAGE_BY_ENTITY,
            self::AXE
        );

        $this->callable = function (EntityDamageByEntityEvent $event, int $level) : void {
            $entity = $event->getEntity();
            $damager = $event->getDamager();

            if($event->isCancelled() || !$entity instanceof Player || !$damager instanceof Player) return;

            if(mt_rand(0, 100) <= $level * 2.5) {
                if($entity->getInventory()->getItemInHand() instanceof Axe) {
                    $event->cancel();
                }
            }
        };
    }

}