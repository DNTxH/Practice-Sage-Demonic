<?php

declare(strict_types = 1);

namespace vale\sage\demonic\enchants\enchantments\type\ultimate;

use pocketmine\entity\effect\Effect;
use pocketmine\event\entity\ProjectileHitEntityEvent;
use pocketmine\item\Durable;
use pocketmine\item\enchantment\ItemFlags;
use pocketmine\player\Player;
use vale\sage\demonic\enchants\CustomEnchant;
use vale\sage\demonic\enchants\CustomEnchantIds;

class EagleEye extends CustomEnchant {

    public function __construct() {
        parent::__construct(
            "Eagle Eye",
            CustomEnchantIds::EAGLEEYE,
            "Chance to deal 1-4 durability damage to ALL armor pieces of enemy player. The # of durability damage dealt is based on how far of a bow shot you hit them with. The further away, the more durability damage",
            5,
            ItemFlags::BOW,
            self::ULTIMATE,
            self::OFFENSIVE,
            self::PROJECTILE_ENTITY,
            self::BOW_2
        );

        $this->callable = function (ProjectileHitEntityEvent $event, int $level) : void {
            $owner = $event->getEntity()->getOwningEntity();
            $hit = $event->getEntityHit();
            $durability = 0;

            if(!$owner instanceof Player || !$hit instanceof Player) return;

            if($owner->getPosition()->distance($hit->getPosition()) > 5) $durability = 1;
            if($owner->getPosition()->distance($hit->getPosition()) > 10) $durability = 2;
            if($owner->getPosition()->distance($hit->getPosition()) > 15) $durability = 3;
            if($owner->getPosition()->distance($hit->getPosition()) > 20) $durability = 4;

            if(mt_rand(0, 100) <= $level * 2.5) {
                foreach($hit->getArmorInventory()->getContents() as $content) {
                    if($content instanceof Durable) $content->applyDamage($durability);
                }
            }
        };
    }

}