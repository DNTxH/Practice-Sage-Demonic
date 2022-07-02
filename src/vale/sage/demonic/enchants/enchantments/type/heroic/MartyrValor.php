<?php

declare(strict_types = 1);

namespace vale\sage\demonic\enchants\enchantments\type\heroic;

use pocketmine\entity\effect\Effect;
use pocketmine\event\entity\EntityDamageByEntityEvent;
use pocketmine\item\enchantment\ItemFlags;
use pocketmine\item\Sword;
use pocketmine\player\Player;
use vale\sage\demonic\enchants\CustomEnchantIds;

class MartyrValor extends HeroicCustomEnchant {

    public function __construct() {
        parent::__construct(
            "Martyr Valor",
            CustomEnchantIds::MARTYRVALOR,
            "Reduces incoming damage while wielding any sword (including heroic swords),",
            5,
            ItemFlags::ARMOR,
            self::HEROIC,
            self::DEFENSIVE,
            self::ENTITY_DAMAGE_BY_ENTITY,
            self::ARMOR,
            CustomEnchantIds::VALOR
        );

        $this->callable = function (EntityDamageByEntityEvent $event, int $level) : void {
            $entity = $event->getEntity();
            $damager = $event->getDamager();
            $reduce = 1 - (0.1 * $level);

            if($event->isCancelled() || !$entity instanceof Player || !$damager instanceof Player) return;

            if($entity->getInventory()->getItemInHand() instanceof Sword) {
                $event->setBaseDamage($event->getBaseDamage() * $reduce);
            }
        };
    }

}