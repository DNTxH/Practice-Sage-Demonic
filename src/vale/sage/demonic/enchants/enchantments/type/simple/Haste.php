<?php

declare(strict_types = 1);

namespace vale\sage\demonic\enchants\enchantments\type\simple;

use pocketmine\data\bedrock\EffectIdMap;
use pocketmine\entity\effect\Effect;
use pocketmine\entity\effect\EffectInstance;
use pocketmine\entity\effect\VanillaEffects;
use pocketmine\event\block\BlockBreakEvent;
use pocketmine\item\enchantment\ItemFlags;
use vale\sage\demonic\enchants\CustomEnchant;
use vale\sage\demonic\enchants\CustomEnchantIds;

class Haste extends CustomEnchant {

    public function __construct() {
        parent::__construct(
            "Haste",
            CustomEnchantIds::HASTE,
            "Allows you to swing your tools faster.",
            3,
            ItemFlags::TOOL,
            self::SIMPLE,
            self::MINING,
            self::BREAK,
            self::TOOL
        );
        $this->callable = function (BlockBreakEvent $event, int $level) : void {
            if($event->isCancelled()) return;

            $player = $event->getPlayer();
            $chance = 5 * $level;
            $duration = 1 * $level * 20;

            if(mt_rand(1, 100) <= $chance) {
                $player->getEffects()->add(new EffectInstance(VanillaEffects::HASTE(), $duration, $level));
            }
        };
    }

}