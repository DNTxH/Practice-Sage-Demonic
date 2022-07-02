<?php

declare(strict_types = 1);

namespace vale\sage\demonic\enchants\enchantments\type\unique;

use pocketmine\utils\Random;
use pocketmine\math\Vector3;
use pocketmine\world\sound\IgniteSound;
use pocketmine\entity\Location;
use pocketmine\event\entity\EntityDamageByEntityEvent;
use pocketmine\item\enchantment\ItemFlags;
use pocketmine\player\Player;
use vale\sage\demonic\enchants\CustomEnchant;
use vale\sage\demonic\enchants\CustomEnchantIds;
use vale\sage\demonic\enchants\utils\CustomPrimedTnt;

class SelfDestruct extends CustomEnchant {

    /** @var array */
    private static array $cooldowns = [];

    public function __construct() {
        parent::__construct(
        "Self Destruct",
        CustomEnchantIds::SELFDESTRUCT,
        "When close to death buffed TnT spawns around you.",
        3,
        ItemFlags::ARMOR,
        self::UNIQUE,
        self::DEFENSIVE,
        self::ENTITY_DAMAGE_BY_ENTITY,
        self::ARMOR
        );

        $this->callable = function (EntityDamageByEntityEvent $event, int $level) : void {
            $entity = $event->getEntity();
            $damager = $event->getDamager();

            if(!$entity instanceof Player || !$damager instanceof Player || $event->isCancelled()) return;

            if(isset(self::$cooldowns[$entity->getUniqueId()->toString()])) {
                if(time() >= self::$cooldowns[$entity->getUniqueId()->toString()]) {
                    unset(self::$cooldowns[$entity->getUniqueId()->toString()]);
                } else {
                    return;
                }
            }

            if($entity->getHealth() <= 4.0) {
                for($i = 0; $i < 3; $i++) {
                    $mot = (new Random())->nextSignedFloat() * M_PI * 2;

                    $tnt = new CustomPrimedTnt(Location::fromObject($entity->getPosition()->add($i + 0.5, $i, $i + 0.5), $entity->getPosition()->getWorld()));
                    $tnt->setFuse(80);
                    $tnt->setWorksUnderwater(true);
                    $tnt->setMotion(new Vector3(-sin($mot) * 0.02, 0.2, -cos($mot) * 0.02));

                    $tnt->spawnToAll();
                    $tnt->broadcastSound(new IgniteSound());
                }

                self::$cooldowns[$entity->getUniqueId()->toString()] = time() + 20;
            }
        };
    }

}