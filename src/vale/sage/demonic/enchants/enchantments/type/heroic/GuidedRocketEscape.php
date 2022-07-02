<?php

declare(strict_types = 1);

namespace vale\sage\demonic\enchants\enchantments\type\heroic;

use pocketmine\entity\effect\Effect;
use pocketmine\event\entity\EntityDamageByEntityEvent;
use pocketmine\item\enchantment\ItemFlags;
use pocketmine\player\Player;
use pocketmine\scheduler\ClosureTask;
use vale\sage\demonic\enchants\CustomEnchantIds;
use vale\sage\demonic\enchants\enchantments\type\soul\Sabotage;
use vale\sage\demonic\Loader;

class GuidedRocketEscape extends HeroicCustomEnchant {

    public function __construct() {
        parent::__construct(
            "Guided Rocket Escape",
            CustomEnchantIds::GUIDEDROCKETESCAPE,
            "Blast off into the air and breifly gain flight for (level x 1s).",
            3,
            ItemFlags::FEET,
            self::HEROIC,
            self::DEFENSIVE,
            self::ENTITY_DAMAGE_BY_ENTITY,
            self::BOOTS,
            CustomEnchantIds::GUIDEDROCKETESCAPE
        );

        $this->callable = function (EntityDamageByEntityEvent $event, int $level) : void {
            $entity = $event->getEntity();
            $damager = $event->getDamager();

            if($event->isCancelled() || !$entity instanceof Player || !$damager instanceof Player) return;

            if(in_array($entity->getUniqueId()->toString(), Sabotage::$sabotaged)) return;

            if($entity->getHealth() <= 4.0) {
                $entity->knockBack($level * 3, $level * 3, $level, $level * 3);
                $entity->setAllowFlight(true);
                $entity->setFlying(true);

                Loader::getInstance()->getScheduler()->scheduleDelayedTask(new ClosureTask(function () use($entity) : void {
                    $entity->setAllowFlight(false);
                    $entity->setFlying(false);
                }), $level * 20);
            }
        };
    }

}