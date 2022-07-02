<?php

declare(strict_types = 1);

namespace vale\sage\demonic\enchants\enchantments\type\unique;

use pocketmine\entity\effect\Effect;
use pocketmine\event\entity\EntityDamageByEntityEvent;
use pocketmine\item\enchantment\ItemFlags;
use pocketmine\player\Player;
use vale\sage\demonic\enchants\CustomEnchant;
use vale\sage\demonic\enchants\CustomEnchantIds;

class SkillSwipe extends CustomEnchant {

    public function __construct() {
        parent::__construct(
            "Skill Swipe",
            CustomEnchantIds::SKILLSWIPE,
            "A chance to steal some of your enemy's XP every time you damage them.",
            5,
            ItemFlags::SWORD,
            self::UNIQUE,
            self::OFFENSIVE,
            self::ENTITY_DAMAGE_BY_ENTITY,
            self::SWORD
        );

        $this->callable = function (EntityDamageByEntityEvent $event, int $level) : void {
            $entity = $event->getEntity();
            $damager = $event->getDamager();

            if(!$entity instanceof Player || !$damager instanceof Player || $event->isCancelled()) return;

            $amount = 10 * $level;

            if(mt_rand(0, 100) <= $level * 2.5) {
                if($entity->getXpManager()->getCurrentTotalXp() >= $amount) {
                    $entity->getXpManager()->subtractXp($amount);
                    $damager->getXpManager()->addXp($amount);
                }
            }
        };
    }

}