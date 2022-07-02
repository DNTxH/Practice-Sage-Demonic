<?php

declare(strict_types = 1);

namespace vale\sage\demonic\enchants\enchantments\type\legendary;

use pocketmine\entity\effect\Effect;
use pocketmine\item\enchantment\ItemFlags;
use pocketmine\player\Player;
use vale\sage\demonic\enchants\CustomEnchant;
use vale\sage\demonic\enchants\CustomEnchantIds;
use vale\sage\demonic\enchants\event\BleedDamageEvent;

class BloodLust extends CustomEnchant {

    public function __construct() {
        parent::__construct(
            "Blood Lust",
            CustomEnchantIds::BLOODLUST,
            "A chance to heal you whenever an enemy player within 7x7 blocks is damaged by the Bleed enchantment.",
            6,
            ItemFlags::TORSO,
            self::LEGENDARY,
            self::DEFENSIVE,
            self::BLEED,
            self::CHESTPLATE
        );

        $this->callable = function (BleedDamageEvent $event, int $level, Player $player) : void {
            if(mt_rand(0, 100) <= $level * 2) {
                if($event->getPlayer()->getPosition()->distance($player->getPosition()) <= 7.0) {
                    $player->setHealth($player->getHealth() + ($level * $event->getDamage()));
                }
            }
        };
    }

}