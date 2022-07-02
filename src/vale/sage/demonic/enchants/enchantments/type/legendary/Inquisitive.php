<?php

declare(strict_types = 1);

namespace vale\sage\demonic\enchants\enchantments\type\legendary;

use pocketmine\event\entity\EntityDeathEvent;
use pocketmine\item\enchantment\ItemFlags;
use vale\sage\demonic\enchants\CustomEnchant;
use vale\sage\demonic\enchants\CustomEnchantIds;

class Inquisitive extends CustomEnchant {

    public function __construct() {
        parent::__construct(
            "Inquisitive",
            CustomEnchantIds::INQUISITIVE,
            "Increases EXP drops from mobs.",
            4,
            ItemFlags::SWORD,
            self::LEGENDARY,
            self::OFFENSIVE,
            self::ENTITY_DEATH,
            self::SWORD
        );

        $this->callable = function (EntityDeathEvent $event, int $level) : void {
            $event->setXpDropAmount((int)$event->getXpDropAmount() * ((0.5 * $level) + 1));
        };
    }

}