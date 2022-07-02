<?php

declare(strict_types = 1);

namespace vale\sage\demonic\enchants\enchantments\type\simple;

use pocketmine\entity\effect\EffectInstance;
use pocketmine\entity\effect\VanillaEffects;
use pocketmine\event\entity\ProjectileHitEntityEvent;
use pocketmine\item\Durable;
use pocketmine\item\enchantment\ItemFlags;
use pocketmine\player\Player;
use vale\sage\demonic\enchants\CustomEnchant;
use vale\sage\demonic\enchants\CustomEnchantIds;

class Healing extends CustomEnchant {

    public function __construct() {
        parent::__construct(
            "Healing",
            CustomEnchantIds::HEALING,
            "Heals friendly players hit with arrow shot by this bow. Also has a chance to increase durability of armor and give absorption/health boost.",
            4,
            ItemFlags::BOW,
            self::SIMPLE,
            self::DEFENSIVE,
            self::PROJECTILE_ENTITY,
            self::BOW_2
        );
        $this->callable = function (ProjectileHitEntityEvent $event, int $level) : void {
            $entity = $event->getEntityHit();
            $shooter = $event->getEntity()->getOwningEntity();

            // TODO check if players are of same faction
            if(!$entity instanceof Player || $shooter !== null || !$shooter instanceof Player) return;

            $entity->setHealth($entity->getHealth() + ($level / 8));

            if(mt_rand(1, 100) <= $level * 2) {
                foreach ($entity->getArmorInventory()->getContents() as $content) {
                    if($content instanceof Durable && ($content->getDamage() - $level) >= 0) {
                        $content->setDamage($content->getDamage() - $level);
                    } else {
                        if($content instanceof Durable) {
                            $content->setDamage(0);
                        }
                    }
                }
                $entity->getEffects()->add(new EffectInstance(VanillaEffects::ABSORPTION(), $level * 20, $level));
                $entity->getEffects()->add(new EffectInstance(VanillaEffects::HEALTH_BOOST(), $level * 20, $level));
            }
        };
    }
}