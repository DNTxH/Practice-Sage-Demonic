<?php
namespace vale\sage\demonic\items\armor\type;
use vale\sage\demonic\items\armor\BaseArmorItem;
use vale\sage\demonic\items\DamageInfo;
use pocketmine\item\ArmorTypeInfo;
use pocketmine\item\enchantment\EnchantmentInstance;
use pocketmine\item\enchantment\VanillaEnchantments;
use pocketmine\item\ItemIdentifier;


/**
 * Class YetiArmorItem
 * @package vale\sage\demonic\items\armor\type
 * @author Jibix
 * @date 06.01.2022 - 18:31
 * @project Genesis
 */
class YetiArmorItem extends BaseArmorItem{

    public function __construct(ItemIdentifier $identifier){
        parent::__construct($identifier);
        self::addEnchantment(new EnchantmentInstance(VanillaEnchantments::PROTECTION(), 4));
        self::addEnchantment(new EnchantmentInstance(VanillaEnchantments::UNBREAKING(), 3));
        self::setCustomName(match ($this->getArmorType()) {
            "helmet" => "§l§bYeti Facemask",
            "chestplate" => "§l§bBloody Yeti Torso",
            "leggings" => "§l§bFuzzy Yeti Leggings",
            "boots" => "§l§bBig-Yeti boots",
        });
        self::setLore([
            "§r",
            "§l§bYETI SET BONUS",
            "§r§b* §r§bDeal +10% more damage to all enemies,",
            "§r§b* §r§bEnemies deal -10% less damage to you.",
            "§r§7(Requires all 4 yeti items.)"
        ]);
    }

    public function getArmorName(): string{
        return "yeti";
    }

    public function getColoredName(): string{
        return "§b" . ucwords($this->getArmorName());
    }

    public function getDamageInfo(): DamageInfo{
        return new DamageInfo([
            "increase" => [
                "default" => 0.1
            ],
            "decrease" => [
                "default" => 0.1
            ]
        ]);
    }
}