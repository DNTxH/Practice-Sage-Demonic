<?php

declare(strict_types = 1);

namespace vale\sage\demonic\enchants\enchantments\type\unique;

use pocketmine\entity\effect\Effect;
use pocketmine\event\entity\EntityDamageByEntityEvent;
use pocketmine\item\enchantment\ItemFlags;
use pocketmine\player\Player;
use pocketmine\scheduler\ClosureTask;
use pocketmine\utils\TextFormat;
use vale\sage\demonic\enchants\CustomEnchant;
use vale\sage\demonic\enchants\CustomEnchantIds;
use vale\sage\demonic\enchants\task\BleedTask;
use vale\sage\demonic\enchants\utils\IndicatorManager;
use vale\sage\demonic\Loader;

class DeepWounds extends CustomEnchant {

    /** @var array */
    public static array $bleeding = [];

    public function __construct() {
        parent::__construct(
            "Deep Wounds",
            CustomEnchantIds::DEEPWOUNDS,
            "Increases the chance of you giving the bleed effect. Prevents enemy bless enchant",
            3,
            ItemFlags::SWORD | ItemFlags::AXE,
            self::SIMPLE,
            self::OFFENSIVE,
            self::ENTITY_DAMAGE_BY_ENTITY,
            self::SWORD | self::AXE
        );

        $this->callable = function (EntityDamageByEntityEvent $event, int $level) : void {
            $entity = $event->getEntity();
            $damager = $event->getDamager();

            if($event->isCancelled() || !$entity instanceof Player || !$damager instanceof Player) return;

            if(mt_rand(0, 100) <= ($level * 2)) {
                if(in_array($entity->getUniqueId()->toString(), self::$bleeding)) return;
                Loader::getInstance()->getScheduler()->scheduleRepeatingTask(new BleedTask($entity), 30);
                self::$bleeding[] = $entity->getUniqueId()->toString();
                IndicatorManager::addTag($damager, $entity, TextFormat::RED . "*BLEEDING*", 90, TextFormat::RED);
            }
        };
    }

}