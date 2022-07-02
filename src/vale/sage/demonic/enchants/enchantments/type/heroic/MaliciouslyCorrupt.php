<?php

declare(strict_types = 1);

namespace vale\sage\demonic\enchants\enchantments\type\heroic;

use pocketmine\entity\effect\Effect;
use pocketmine\event\entity\EntityDamageByEntityEvent;
use pocketmine\item\enchantment\ItemFlags;
use pocketmine\player\Player;
use pocketmine\scheduler\ClosureTask;
use vale\sage\demonic\enchants\CustomEnchantIds;
use vale\sage\demonic\Loader;

class MaliciouslyCorrupt extends HeroicCustomEnchant {

    /** @var array */
    public static array $maliciouslyCorrupted = [];

    public function __construct() {
        parent::__construct(
            "Maliciously Corrupt",
            CustomEnchantIds::MALICIOUSLYCORRUPT,
            "In addition to an amplified version of the effects of Corrupt, enemies afflicted with Maliciously Corrupt have successful Block enchantment procs 20% less often.",
            4,
            ItemFlags::AXE,
            self::HEROIC,
            self::OFFENSIVE,
            self::ENTITY_DAMAGE_BY_ENTITY,
            self::AXE,
            CustomEnchantIds::CORRUPT
        );

        $this->callable = function (EntityDamageByEntityEvent $event, int $level) : void {
            $entity = $event->getEntity();
            $damager = $event->getDamager();

            if($event->isCancelled() || !$entity instanceof Player || !$damager instanceof Player) return;

            if(mt_rand(0, 100) <= $level * 3) {
                for($i = 20; $i < 100; $i += 20) {
                    Loader::getInstance()->getScheduler()->scheduleDelayedTask(new ClosureTask(function () use($entity, $level) : void {
                        $entity->setHealth($entity->getHealth() - ($level / 2.5));
                    }), $i);
                }

                if(in_array($entity->getUniqueId()->toString(), self::$maliciouslyCorrupted)) return;

                self::$maliciouslyCorrupted[] = $entity->getUniqueId()->toString();

                Loader::getInstance()->getScheduler()->scheduleDelayedTask(new ClosureTask(function () use($entity) : void {
                    unset(self::$maliciouslyCorrupted[array_search($entity->getUniqueId()->toString(), self::$maliciouslyCorrupted)]);
                }), 100);
            }
        };
    }
}