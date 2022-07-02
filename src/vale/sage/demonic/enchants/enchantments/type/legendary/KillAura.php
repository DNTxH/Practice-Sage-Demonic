<?php

declare(strict_types = 1);

namespace vale\sage\demonic\enchants\enchantments\type\legendary;

use pocketmine\entity\effect\Effect;
use pocketmine\entity\Human;
use pocketmine\entity\Living;
use pocketmine\event\entity\EntityDamageByEntityEvent;
use pocketmine\event\entity\EntityDamageEvent;
use pocketmine\item\enchantment\ItemFlags;
use pocketmine\player\Player;
use vale\sage\demonic\enchants\CustomEnchant;
use vale\sage\demonic\enchants\CustomEnchantIds;
use vale\sage\demonic\spawner\Mobstacker;

class KillAura extends CustomEnchant {

    public function __construct() {
        parent::__construct(
            "Kill Aura",
            CustomEnchantIds::KILLAURA,
            "Chance to kill multiple monsters in a stack each death event.",
            5,
            CustomEnchantIds::KILLAURA,
            self::LEGENDARY,
            self::OFFENSIVE,
            self::ENTITY_DAMAGE,
            self::SWORD
        );

        $this->callable = function (EntityDamageEvent $event, int $level) : void {
            $entity = $event->getEntity();

            if(!$entity instanceof Living || $entity instanceof Human){
                return;
            }

            if(mt_rand(0, 100) <= $level * 4) {
                $mobStacker = new Mobstacker($entity);

                if($entity->getHealth() - $event->getFinalDamage() <= 0){
                    $cause = null;

                    if($event instanceof EntityDamageByEntityEvent){
                        $player = $event->getDamager();
                        if($player instanceof Player) {
                            $cause = $player;
                        }
                    }

                    if(!$mobStacker->isStacked()) return;

                    if(($c = $mobStacker->getStackAmount()) <= $level + 1) {
                        for($i = $c; $i >= 1; $i--) {
                            $mobStacker->removeStackV2();
                        }
                        $event->cancel();
                        $entity->setHealth($entity->getMaxHealth());
                        return;
                    }

                    for($i = $level; $i >= 0; $i--) {
                        $mobStacker->removeStackV2();
                    }

                    $event->cancel();
                    $entity->setHealth($entity->getMaxHealth());
                }
            }
        };
    }


}