<?php

declare(strict_types = 1);

namespace vale\sage\demonic\enchants\enchantments\type\unique;

use pocketmine\block\BlockBreakInfo;
use pocketmine\entity\effect\Effect;
use pocketmine\event\block\BlockBreakEvent;
use pocketmine\item\enchantment\ItemFlags;
use pocketmine\utils\TextFormat;
use vale\sage\demonic\enchants\CustomEnchant;
use vale\sage\demonic\enchants\CustomEnchantIds;

class Telepathy extends CustomEnchant {

    public function __construct() {
        parent::__construct(
            "Telepathy",
            CustomEnchantIds::TELEPATHY,
            "Automatically places blocks broken by tools in your inventory.",
            4,
            ItemFlags::TOOL,
            self::UNIQUE,
            self::MINING,
            self::BREAK,
            self::TOOL
        );

        $this->callable = function (BlockBreakEvent $event, int $level) : void {
            if(!$event->isCancelled()) return;

            if(mt_rand(1, 100) <= $level * 25) {
                foreach($event->getDrops() as $drop) {
                    if($event->getPlayer()->getInventory()->canAddItem($drop)) {
                        $event->getPlayer()->getInventory()->addItem($drop);
                    } else {
                        $event->getPlayer()->getWorld()->dropItem($event->getPlayer()->getPosition(), $drop);
                        $event->getPlayer()->sendTitle(TextFormat::RED . "INVENTORY FULL");
                    }
                }
            }
        };
    }


}