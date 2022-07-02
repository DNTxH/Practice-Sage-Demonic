<?php

declare(strict_types = 1);

namespace vale\sage\demonic\enchants\enchantments\type\elite;

use pocketmine\entity\effect\Effect;
use pocketmine\event\entity\EntityDamageByEntityEvent;
use pocketmine\item\enchantment\ItemFlags;
use pocketmine\player\Player;
use pocketmine\world\particle\SmokeParticle;
use vale\sage\demonic\enchants\CustomEnchant;
use vale\sage\demonic\enchants\CustomEnchantIds;

class Smokebomb extends CustomEnchant {

    public function __construct() {
        parent::__construct(
            "Smokebomb",
            CustomEnchantIds::SMOKEBOMB,
            "When you are near death, you will spawn a smoke bomb to distract your enemies.",
            8,
            ItemFlags::HEAD,
            self::ELITE,
            self::DEFENSIVE,
            self::ENTITY_DAMAGE_BY_ENTITY,
            self::HELMET
        );

        $this->callable = function (EntityDamageByEntityEvent $event, int $level) : void {
            $entity = $event->getEntity();
            $damager = $event->getDamager();

            if($event->isCancelled() || !$entity instanceof Player || !$damager instanceof Player) return;

            if($entity->getHealth() <= 6.0) {
                for($i = 0; $i < 4; $i++) {
                    $entity->getWorld()->addParticle($entity->getLocation()->add($i, $i, $i), new SmokeParticle($i));
                }
            }
        };
    }
}