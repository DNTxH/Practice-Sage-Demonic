<?php
namespace vale\sage\demonic\items\weapon\type;
use vale\sage\demonic\items\weapon\BaseWeaponItem;
use vale\sage\demonic\items\DamageInfo;
use pocketmine\item\enchantment\EnchantmentInstance;
use pocketmine\item\enchantment\VanillaEnchantments;
use pocketmine\item\ItemIdentifier;


/**
 * Class RangerWeaponItem
 * @package vale\sage\demonic\items\weapon\type
 * @author Jibix
 * @date 06.01.2022 - 20:42
 * @project Genesis
 */
class RangerWeaponItem extends BaseWeaponItem{

    public function __construct(ItemIdentifier $identifier){
        parent::__construct($identifier);
        self::addEnchantment(new EnchantmentInstance(VanillaEnchantments::POWER(), 5));
        self::addEnchantment(new EnchantmentInstance(VanillaEnchantments::UNBREAKING(), 3));
        self::addEnchantment(new EnchantmentInstance(VanillaEnchantments::FLAME(), 2));
        self::addEnchantment(new EnchantmentInstance(VanillaEnchantments::INFINITY(), 1));
        self::setCustomName("§l§aRanger Bow");
        self::setLore([
            "§r",
            "§l§aRANGER WEAPON BONUS",
            "§r§a* §r§aRanger bow grants +30% increased bow damage.",
            "§r§7(Requires all 4 ranger items.)"
        ]);
    }

    public function getWeaponType(): string{
        return "ranger";
    }

    public function getDamageInfo(): DamageInfo{
        return new DamageInfo([
            "increase" => [
                "default" => 0.30
            ]
        ]);
    }
}