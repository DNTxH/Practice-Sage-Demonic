<?php

declare(strict_types = 1);

namespace vale\sage\demonic\enchants\enchantments\type\unique;

use pocketmine\entity\effect\Effect;
use pocketmine\event\player\PlayerDeathEvent;
use pocketmine\item\enchantment\ItemFlags;
use vale\sage\demonic\enchants\CustomEnchant;
use vale\sage\demonic\enchants\CustomEnchantIds;

class Lifebloom extends CustomEnchant {

    public function __construct() {
        parent::__construct(
            "Lifebloom",
            CustomEnchantIds::LIFEBLOOM,
            "Heals allies and truces within the chunk on your death.",
            5,
            ItemFlags::LEGS,
            self::UNIQUE,
            self::DEATH,
            self::PLAYER_DEATH,
            self::LEGGINGS
        );

        $this->callable = function (PlayerDeathEvent $event, int $level) : void {
            foreach($event->getPlayer()->getWorld()->getChunkPlayers($event->getPlayer()->getLocation()->getX(), $event->getPlayer()->getLocation()->getZ()) as $player) {
                // does it for players who view the chunk, will redo this later to loop checking if players within the world have the same chunk as the player
                // todo redo and check fac members/allies

                if($level === 5) {
                    $multi = 1;
                } elseif($level === 4) {
                    $multi = 2;
                } elseif($level === 3) {
                    $multi = 3;
                } elseif ($level === 2) {
                    $multi = 4;
                } else {
                    $multi = 5;
                }

                $player->setHealth($player->getHealth() + $player->getMaxHealth() / $multi);
            }
        };
    }
}