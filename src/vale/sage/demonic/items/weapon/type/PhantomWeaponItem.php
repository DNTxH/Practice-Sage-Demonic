<?php
namespace vale\sage\demonic\items\weapon\type;
use vale\sage\demonic\items\weapon\BaseWeaponItem;
use vale\sage\demonic\items\DamageInfo;
use pocketmine\item\enchantment\EnchantmentInstance;
use pocketmine\item\enchantment\VanillaEnchantments;
use pocketmine\item\ItemIdentifier;


/**
 * Class PhantomWeaponItem
 * @package vale\sage\demonic\items\weapon\type
 * @author Jibix
 * @date 06.01.2022 - 20:34
 * @project Genesis
 */
class PhantomWeaponItem extends BaseWeaponItem{

    public function __construct(ItemIdentifier $identifier){
        parent::__construct($identifier);
        self::addEnchantment(new EnchantmentInstance(VanillaEnchantments::SHARPNESS(), 5));
        self::addEnchantment(new EnchantmentInstance(VanillaEnchantments::UNBREAKING(), 3));
        self::setCustomName("§l§cPhantom Scythe");
        self::setLore([
            "§r",
            "§l§cPHANTOM WEAPON BONUS",
            "§r§c* §r§cDeal +5% damage to all enemies.",
            "§r§c* §r§cTake -5% damage from all enemies.",
            "§r§7(Requires all 4 phantom items.)"
        ]);
    }

    public function getWeaponType(): string{
        return "phantom";
    }

    public function getDamageInfo(): DamageInfo{
        return new DamageInfo([
            "increase" => [
                "default" => 0.05
            ],
            "decrease" => [
                "default" => 0.05
            ]
        ]);
    }
}