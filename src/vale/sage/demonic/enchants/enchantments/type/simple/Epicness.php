<?php

declare(strict_types = 1);

namespace vale\sage\demonic\enchants\enchantments\type\simple;

use pocketmine\event\entity\EntityDamageByEntityEvent;
use pocketmine\item\enchantment\ItemFlags;
use pocketmine\player\Player;
use pocketmine\world\particle\RedstoneParticle;
use pocketmine\world\sound\XpCollectSound;
use vale\sage\demonic\enchants\CustomEnchant;
use vale\sage\demonic\enchants\CustomEnchantIds;

class Epicness extends CustomEnchant {

    public function __construct() {
        parent::__construct(
            "Epicness",
            CustomEnchantIds::EPICNESS,
            "Gives particles and sound effects.",
            3, ItemFlags::SWORD,
            self::SIMPLE,
            self::OFFENSIVE,
            self::ENTITY_DAMAGE_BY_ENTITY,
            self::SWORD
        );
        $this->callable = function (EntityDamageByEntityEvent $event, int $level) : void {
            $damager = $event->getDamager();

            if(!$damager instanceof Player) return;

            $damager->getWorld()->addParticle($damager->getPosition(), new RedstoneParticle($level));

            if(mt_rand(1, 100) <= $level * 2) {
                $damager->getWorld()->addSound($damager->getPosition(), new XpCollectSound());
            }
        };
    }
}