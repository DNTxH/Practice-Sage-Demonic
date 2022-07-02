<?php

declare(strict_types = 1);

namespace vale\sage\demonic\enchants\enchantments\type\heroic;

use pocketmine\entity\effect\Effect;
use pocketmine\event\entity\ProjectileHitEntityEvent;
use pocketmine\item\enchantment\ItemFlags;
use pocketmine\player\Player;
use pocketmine\scheduler\ClosureTask;
use vale\sage\demonic\enchants\CustomEnchantIds;
use vale\sage\demonic\Loader;

class BidirectionalTeleportation extends HeroicCustomEnchant {

    public function __construct() {
        parent::__construct(
            "Bidirectional Teleportation",
            CustomEnchantIds::BIDIRECTIONALTELEPORTATION,
            "Chance to teleport an enemy towards you, or trap them for 1-2s, requires Teleportation V enchant on item to apply.",
            5,
            ItemFlags::BOW,
            self::HEROIC,
            self::OFFENSIVE,
            self::PROJECTILE_ENTITY,
            self::BOW_2,
            CustomEnchantIds::TELEPORTATION
        );

        $this->callable = function (ProjectileHitEntityEvent $event, int $level) : void {
            $owner = $event->getEntity()->getOwningEntity();
            $hit = $event->getEntityHit();

            if(!$owner instanceof Player || $hit instanceof Player) return;

            if(mt_rand(0, 100) <= $level * 2) {
                $hit->teleport($owner->getPosition());
            } elseif(mt_rand(0, 100) <= $level * 3) {
                if(!$hit->isImmobile()) {
                    $hit->setImmobile(true);

                    Loader::getInstance()->getScheduler()->scheduleDelayedTask(new ClosureTask(function () use($hit) : void {
                        $hit->setImmobile(false);
                    }), mt_rand(1, 2));
                }
            }
        };
    }


}