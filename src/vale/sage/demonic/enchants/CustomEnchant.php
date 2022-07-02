<?php


namespace vale\sage\demonic\enchants;

use pocketmine\entity\effect\Effect;
use vale\sage\demonic\enchants\enchantments\EnchantmentsManager;
use pocketmine\item\enchantment\Enchantment;
use pocketmine\item\enchantment\ItemFlags;
use pocketmine\item\enchantment\Rarity;

abstract class CustomEnchant extends Enchantment
{
    public $callable;

    const SIMPLE    = 0;
    const UNIQUE    = 1;
    const ELITE     = 2;
    const ULTIMATE  = 3;
    const LEGENDARY = 4;
    const SOUL      = 5;
    const HEROIC    = 6;
    const MASTERY   = 7;

    /** @var string */
    private string $description;

    /** @var int */
    private int $tier;

    /** @var int */
    private int $type;

    /** @var int */
    private int $id;

    /** @var Effect|null */
    private Effect|null $effect = null;

    /** @var int */
    private int $amplifier = -1;

    const OFFENSIVE = 0;
    const DEFENSIVE = 1;
    const MINING    = 2;
    const MOVEMENT  = 3;
    const BOW       = 4;
    const DEATH     = 5;

    /** @var int */
    private int $eventType;

    const ENTITY_DAMAGE_BY_ENTITY = 0;
    const BREAK = 1;
    const PLACE = 2;
    const KILL = 3;
    const PROJECTILE = 4;
    const PROJECTILE_ENTITY = 5;
    const EFFECT = 6;
    const PLAYER_DEATH = 7;
    const ENTITY_DAMAGE = 8;
    const ENTITY_DEATH = 9;
    const DISARMOR = 10;
    const BLEED = 11;
    const SOULTRAP = 12;
    CONST TODO = 13;
    const METAPHYSICAL = 14;
    const SILENCE = 15;

    /** @var int */
    private int $equipType;

    const HELMET = 0;
    const CHESTPLATE = 1;
    const LEGGINGS = 2;
    const BOOTS = 3;
    const SWORD = 4;
    const AXE = 5;
    const ARMOR = 6;
    const TOOL = 7;
    const BOW_2 = 8;
    const PICKAXE = 9;

    /**
     * @param string
     * @param int $id
     * @param string $enchantDescription
     * @param int $maxLevel
     * @param int $flag
     * @param int $tier
     * @param int $type
     * @param int $secondaryFlag
     * @param int $eventType
     * @param int $equipType
     * @param Effect|null $effect
     * @param int $amplifier
     */
    public function __construct(string $name, int $id, string $enchantDescription, int $maxLevel, int $flag, int $tier, int $type, int $eventType, int $equipType, Effect $effect = null, int $amplifier = -1, int $secondaryFlag = ItemFlags::NONE) {
        parent::__construct($name, Rarity::RARE, $flag, $secondaryFlag, $maxLevel);
        $this->description = $enchantDescription;
        $this->tier = $tier;
        $this->id = $id;
        $this->type = $type;
        $this->eventType = $eventType;
        $this->effect = $effect;
        $this->amplifier = $amplifier;
        $this->equipType = $equipType;
    }

    /**
     * @return int
     */
    public function getTier() : int {
        return $this->tier;
    }

    /**
     * @return string
     */
    public function getDescription() : string {
        return $this->description;
    }

    /**
     * @return callable
     */
    public function getCallable() : callable {
        return $this->callable;
    }

    /**
     * @return int
     */
    public function getAction() : int {
        return $this->type;
    }

    /**
     * @return int
     */
    public function getId() : int {
        return $this->id;
    }

    /**
     * @return int
     */
    public function getEventType(): int {
        return $this->eventType;
    }

    /**
     * @return Effect|null
     */
    public function getEffect() : ?Effect {
        return $this->effect;
    }

    /**
     * @return int
     */
    public function getAmplifier() : int {
        return $this->amplifier;
    }

    /**
     * @return int
     */
    public function getEquipType() : int {
        return $this->equipType;
    }
}