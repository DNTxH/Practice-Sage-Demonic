<?php

declare(strict_types = 1);

namespace vale\sage\demonic\enchants\enchantments\type\ultimate;

use pocketmine\entity\effect\Effect;
use pocketmine\event\entity\EntityDamageByEntityEvent;
use pocketmine\item\enchantment\ItemFlags;
use pocketmine\player\Player;
use pocketmine\scheduler\ClosureTask;
use vale\sage\demonic\enchants\CustomEnchant;
use vale\sage\demonic\enchants\CustomEnchantIds;
use vale\sage\demonic\Loader;

class Corrupt extends CustomEnchant {

    public function __construct() {
        parent::__construct(
            "Corrupt",
            CustomEnchantIds::CORRUPT,
            "Deals damage overtime to enemy players.",
            4,
            ItemFlags::AXE,
            self::ULTIMATE,
            self::OFFENSIVE,
            self::ENTITY_DAMAGE_BY_ENTITY,
            self::AXE
        );

        $this->callable = function (EntityDamageByEntityEvent $event, int $level) : void {
            $entity = $event->getEntity();
            $damager = $event->getDamager();

            if($event->isCancelled() || !$entity instanceof Player || !$damager instanceof Player) return;

            if(mt_rand(0, 100) <= $level * 2) {
                for($i = 20; $i < 100; $i += 20) {
                    Loader::getInstance()->getScheduler()->scheduleDelayedTask(new ClosureTask(function () use($entity, $level) : void {
                        $entity->setHealth($entity->getHealth() - ($level / 5));
                    }), $i);
                }
            }
        };
    }

}