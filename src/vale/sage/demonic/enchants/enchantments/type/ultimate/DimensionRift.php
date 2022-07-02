<?php

declare(strict_types = 1);

namespace vale\sage\demonic\enchants\enchantments\type\ultimate;

use pocketmine\block\VanillaBlocks;
use pocketmine\entity\effect\Effect;
use pocketmine\event\entity\ProjectileHitEntityEvent;
use pocketmine\item\enchantment\ItemFlags;
use pocketmine\player\Player;
use vale\sage\demonic\enchants\CustomEnchant;
use vale\sage\demonic\enchants\CustomEnchantIds;

class DimensionRift extends CustomEnchant {

    public function __construct() {
        parent::__construct(
            "Dimension Rift",
            CustomEnchantIds::DIMENSIONRIFT,
            "Chance to turn blocks underneath target to soul sand, and possibly webs ontop.",
            4,
            ItemFlags::BOW,
            self::ULTIMATE,
            self::OFFENSIVE,
            self::PROJECTILE_ENTITY,
            self::BOW_2
        );

        $this->callable = function (ProjectileHitEntityEvent $event, int $level) : void {
            $entity = $event->getEntityHit();
            $t = mt_rand(0, 100);

            if(!$entity instanceof Player) return;

            if($t <= $level * 2) {
                $entity->getWorld()->setBlock($entity->getPosition()->subtract(0, 0, 1), VanillaBlocks::SOUL_SAND());
                if($t <= $level * 1.5) {
                    $entity->getWorld()->setBlock($entity->getPosition()->add(0, 0, 1), VanillaBlocks::COBWEB());
                }
            }
        };
    }


}