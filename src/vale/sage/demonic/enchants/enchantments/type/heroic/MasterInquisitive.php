<?php

declare(strict_types = 1);

namespace vale\sage\demonic\enchants\enchantments\type\heroic;

use pocketmine\entity\effect\Effect;
use pocketmine\event\entity\EntityDeathEvent;
use pocketmine\item\enchantment\ItemFlags;
use vale\sage\demonic\enchants\CustomEnchantIds;

class MasterInquisitive extends HeroicCustomEnchant {

    public function __construct() {
        parent::__construct(
            "Master Inquisitive",
            CustomEnchantIds::MASTERINQUISITIVE,
            "Massively increases EXP drops from mobs. Requires Inquisitive IV enchant on item to apply.",
            4,
            ItemFlags::SWORD,
            self::HEROIC,
            self::OFFENSIVE,
            self::ENTITY_DEATH,
            self::SWORD,
            CustomEnchantIds::INQUISITIVE
        );

        $this->callable = function (EntityDeathEvent $event, int $level) : void {
            $event->setXpDropAmount((int)$event->getXpDropAmount() * ($level + 1));
        };
    }
}