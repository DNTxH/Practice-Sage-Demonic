<?php

declare(strict_types = 1);

namespace vale\sage\demonic\enchants\enchantments\type\simple;

use pocketmine\entity\Entity;
use pocketmine\event\entity\ProjectileHitEvent;
use pocketmine\item\enchantment\ItemFlags;
use pocketmine\network\mcpe\protocol\AddActorPacket;
use vale\sage\demonic\enchants\CustomEnchant;
use vale\sage\demonic\enchants\CustomEnchantIds;

class Lightning extends CustomEnchant {

    public function __construct() {
        parent::__construct(
            "Lightning",
            CustomEnchantIds::LIGHTNING,
            "A chance to strike lightning where you strike.",
            3,
            ItemFlags::BOW,
            self::SIMPLE,
            self::OFFENSIVE,
            self::PROJECTILE,
            self::BOW
        );
        $this->callable = function(ProjectileHitEvent $event, int $level) : void {
            $entity = $event->getEntity();
            $chance = 3 * $level;
            $pos = $event->getRayTraceResult()->getHitVector();
            if(mt_rand(1, 100) <= $chance) {
                AddActorPacket::create(Entity::nextRuntimeId(), 1, "minecraft:lightning_bolt", $pos, null, $entity->getLocation()->getYaw(), $entity->getLocation()->getPitch(), 0.0, [], [], []);
            }
        };
    }

}