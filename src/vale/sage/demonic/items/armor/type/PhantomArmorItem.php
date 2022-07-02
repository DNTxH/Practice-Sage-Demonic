<?php
namespace vale\sage\demonic\items\armor\type;
use vale\sage\demonic\items\armor\BaseArmorItem;
use vale\sage\demonic\items\DamageInfo;
use pocketmine\event\entity\EntityDamageEvent;
use pocketmine\item\ArmorTypeInfo;
use pocketmine\item\enchantment\EnchantmentInstance;
use pocketmine\item\enchantment\VanillaEnchantments;
use pocketmine\item\ItemIdentifier;
use pocketmine\player\Player;


/**
 * Class PhantomArmorItem
 * @package vale\sage\demonic\items\armor\type
 * @author Jibix
 * @date 06.01.2022 - 18:05
 * @project Genesis
 */
class PhantomArmorItem extends BaseArmorItem{

    public function __construct(ItemIdentifier $identifier){
        parent::__construct($identifier);
        self::addEnchantment(new EnchantmentInstance(VanillaEnchantments::PROTECTION(), 4));
        self::addEnchantment(new EnchantmentInstance(VanillaEnchantments::UNBREAKING(), 3));
        self::setCustomName(match ($this->getArmorType()) {
            "helmet" => "§l§cPhantom Hood",
            "chestplate" => "§l§cPhantom Shroud",
            "leggings" => "§l§cPhantom Robeset",
            "boots" => "§l§cPhantom Sandals",
        });
        self::setLore([
            "§r",
            "§l§cPHANTOM SET BONUS",
            "§r§c* §r§cDeal +35% more damage to all enemies.",
            "§r§c* §r§cTake -10% damage from all enemies.",
            "§r§7(Requires all 4 phantom items.)"
        ]);
    }

    public function getArmorName(): string{
        return "phantom";
    }

    public function getColoredName(): string{
        return "§c" . ucwords($this->getArmorName());
    }

    public function getDamageInfo(): DamageInfo{
        return new DamageInfo([
            "increase" => [
                "default" => 0.35
            ],
            "decrease" => [
                "default" => 0.1
            ]
        ]);
    }
}