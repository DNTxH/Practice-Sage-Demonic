<?php

declare(strict_types = 1);

namespace vale\sage\demonic\enchants\enchantments\type\ultimate;

use pocketmine\entity\effect\Effect;
use pocketmine\entity\effect\EffectInstance;
use pocketmine\entity\effect\VanillaEffects;
use pocketmine\event\entity\EntityDamageByEntityEvent;
use pocketmine\item\enchantment\ItemFlags;
use pocketmine\player\Player;
use vale\sage\demonic\enchants\CustomEnchant;
use vale\sage\demonic\enchants\CustomEnchantIds;

class AvengingAngel extends CustomEnchant {

    public function __construct() {
        parent::__construct(
            "Avenging Angel",
            CustomEnchantIds::AVENGINGANGEL,
            "Heal to full HP when an ally dies within upto 100 blocks of your location and receive absorption heats for up to 10 seconds",
            4,
            ItemFlags::ARMOR,
            self::ULTIMATE,
            self::DEFENSIVE,
            self::PLAYER_DEATH,
            self::ARMOR
        );

        $this->callable = function (Player $player, int $level) : void {
            $player->setHealth($player->getMaxHealth());
            $player->getEffects()->add(new EffectInstance(VanillaEffects::ABSORPTION(), $level * 2.5 * 20, $level));
        };
    }

}