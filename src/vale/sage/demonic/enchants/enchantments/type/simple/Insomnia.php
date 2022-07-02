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
 * Class Insomnia
 * @package vale\sage\demonic\enchants\enchantments\simple
 * @author Jibix
 * @date 16.01.2022 - 21:40
 * @project Genesis-Workspace
 */
class Insomnia extends CustomEnchant{

    public function __construct(){
        parent::__construct(
            "Insomnia",
            CustomEnchantIds::INSOMNIA,
            "A chance to give slowness, slow swinging and confusion.",
            7,
            ItemFlags::SWORD,
            self::SIMPLE,
            self::OFFENSIVE,
            self::ENTITY_DAMAGE_BY_ENTITY,
            self::AXE
        );
        $this->callable = function (EntityDamageByEntityEvent $event, int $level): void{
            $entity = $event->getEntity();

            if(!$entity instanceof Player) return;

            $chance = 3 * $level;
            $duration = 0.5 * $level * 20;
            if (mt_rand(0, 100) <= $chance) {
                $entity->getEffects()->add(new EffectInstance(VanillaEffects::SLOWNESS(), $duration, 1, true));
                $entity->getEffects()->add(new EffectInstance(VanillaEffects::NAUSEA(), $duration, 1, true));
                $entity->getEffects()->add(new EffectInstance(VanillaEffects::MINING_FATIGUE(), $duration, 1, true));
            }
        };
    }
}