<?php

declare(strict_types = 1);

namespace vale\sage\demonic\enchants\enchantments\type\legendary;

use pocketmine\entity\effect\Effect;
use pocketmine\event\entity\EntityDamageByEntityEvent;
use pocketmine\item\enchantment\ItemFlags;
use pocketmine\player\Player;
use pocketmine\scheduler\ClosureTask;
use vale\sage\demonic\enchants\CustomEnchant;
use vale\sage\demonic\enchants\CustomEnchantIds;
use vale\sage\demonic\Loader;

class Overwhelm extends CustomEnchant {

    /** @var array */
    public static array $overwhelmed = [];

    public function __construct() {
        parent::__construct(
            "Overwhelm",
            CustomEnchantIds::OVERWHELM,
            "Chance to disable enemy's ability to inflict Reflective Block on you for 6s with every hit.",
            4,
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

            if(in_array($entity->getUniqueId()->toString(), self::$overwhelmed)) return;

            if(mt_rand(0, 100) <= $level * 3) {
                self::$overwhelmed[] = $entity->getUniqueId()->toString();

                Loader::getInstance()->getScheduler()->scheduleDelayedTask(new ClosureTask(function () use($entity) : void {
                    unset(self::$overwhelmed[array_search($entity->getUniqueId()->toString(), self::$overwhelmed)]);
                }), 120);
            }
        };
    }
}