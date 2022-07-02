<?php
    
namespace vale\sage\demonic\enchants\enchantments\type\simple;

use pocketmine\player\Player;
use vale\sage\demonic\enchants\CustomEnchant;
use pocketmine\entity\effect\EffectInstance;
use pocketmine\entity\effect\VanillaEffects;
use pocketmine\event\entity\EntityDamageByEntityEvent;
use pocketmine\item\enchantment\ItemFlags;
use vale\sage\demonic\enchants\CustomEnchantIds;


/**
 * Class Confusion
 * @package vale\sage\demonic\enchants\enchantments\simple
 * @author Jibix
 * @date 16.01.2022 - 21:40
 * @project Genesis-Workspace
 */
class Confusion extends CustomEnchant{

    public function __construct(){
        parent::__construct(
            "Confusion",
            CustomEnchantIds::CONFUSION,
            "A chance to deal nausea to your victim.",
            3,
            ItemFlags::AXE,
            self::SIMPLE,
            self::OFFENSIVE,
            self::ENTITY_DAMAGE_BY_ENTITY,
            self::AXE
        );
        $this->callable = function (EntityDamageByEntityEvent $event, int $level): void{
            $entity = $event->getEntity();
            $chance = 5 * $level;
            $duration = 1 * $level * 20;

            if(!$entity instanceof Player) return;

            if (mt_rand(0, 100) <= $chance) {
                $entity->getEffects()->add(new EffectInstance(VanillaEffects::NAUSEA(), $duration, 1, true));
            }
        };
    }
}