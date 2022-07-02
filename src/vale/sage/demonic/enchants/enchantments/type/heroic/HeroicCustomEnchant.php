<?php

declare(strict_types = 1);

namespace vale\sage\demonic\enchants\enchantments\type\heroic;

use pocketmine\entity\effect\Effect;
use pocketmine\item\enchantment\ItemFlags;
use vale\sage\demonic\enchants\CustomEnchant;

class HeroicCustomEnchant extends CustomEnchant {

    /** @var int */
    private int $childId;

    /**
     * @param string $name
     * @param int $id
     * @param string $enchantDescription
     * @param int $maxLevel
     * @param int $flag
     * @param int $tier
     * @param int $type
     * @param int $eventType
     * @param int $equipType
     * @param int $childId
     * @param Effect|null $effect
     * @param int $amplifier
     * @param int $secondaryFlag
     */
    public function __construct(string $name, int $id, string $enchantDescription, int $maxLevel, int $flag, int $tier, int $type, int $eventType, int $equipType, int $childId, Effect $effect = null, int $amplifier = -1, int $secondaryFlag = ItemFlags::NONE) {
        $this->childId = $childId;
        parent::__construct($name, $id, $enchantDescription, $maxLevel, $flag, $tier, $type, $eventType, $equipType, $effect, $amplifier, $secondaryFlag);
    }

    /**
     * @return int
     */
    public function getChildId() : int {
        return $this->childId;
    }
}