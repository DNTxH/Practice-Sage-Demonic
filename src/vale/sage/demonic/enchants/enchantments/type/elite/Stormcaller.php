<?php

declare(strict_types = 1);

namespace vale\sage\demonic\enchants\enchantments\type\elite;

use pocketmine\entity\effect\Effect;
use pocketmine\entity\Entity;
use pocketmine\event\entity\EntityDamageByEntityEvent;
use pocketmine\item\enchantment\ItemFlags;
use pocketmine\network\mcpe\protocol\AddActorPacket;
use pocketmine\player\Player;
use vale\sage\demonic\enchants\CustomEnchant;
use vale\sage\demonic\enchants\CustomEnchantIds;

class Stormcaller extends CustomEnchant {

    public function __construct() {
        parent::__construct(
            "Stormcaller",
            CustomEnchantIds::STORMCALLER,
            "Strikes lightning on attacking players.",
            4,
            ItemFlags::ARMOR,
            self::ELITE,
            self::DEFENSIVE,
            self::ENTITY_DAMAGE_BY_ENTITY,
            self::ARMOR
        );

        $this->callable = function (EntityDamageByEntityEvent $event, int $level) : void {
            $entity = $event->getEntity();
            $damager = $event->getDamager();

            if($event->isCancelled() || $entity instanceof Player || !$damager instanceof Player) return;

            if(mt_rand(0, 100) <= $level * 2) {
                AddActorPacket::create(Entity::nextRuntimeId(), 1, "minecraft:lightning_bolt", $entity->getPosition(), null, $entity->getLocation()->getYaw(), $entity->getLocation()->getPitch(), 0.0, [], [], []);
            }
        };
    }

}