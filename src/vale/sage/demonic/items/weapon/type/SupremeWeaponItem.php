<?php
namespace vale\sage\demonic\items\weapon\type;
use vale\sage\demonic\items\weapon\BaseWeaponItem;
use vale\sage\demonic\items\DamageInfo;
use pocketmine\item\enchantment\EnchantmentInstance;
use pocketmine\item\enchantment\VanillaEnchantments;
use pocketmine\item\ItemIdentifier;


/**
 * Class SupremeWeaponItem
 * @package vale\sage\demonic\items\weapon\type
 * @author Jibix
 * @date 06.01.2022 - 20:44
 * @project Genesis
 */
class SupremeWeaponItem extends BaseWeaponItem{

    public function __construct(ItemIdentifier $identifier){
        parent::__construct($identifier);
        self::addEnchantment(new EnchantmentInstance(VanillaEnchantments::SHARPNESS(), 5));
        self::addEnchantment(new EnchantmentInstance(VanillaEnchantments::UNBREAKING(), 3));
        self::setCustomName("§l§4Supreme Fanny Pack");
        self::setLore([
            "§r",
            "§l§4SUPREME WEAPON BONUS",
            "§r§4* §r§4Deal +20% damage to all enemies.",
            "§r§4* §r§4Enemies deal -10% less damage to you.",
            "§r§7(Requires all 4 supreme items.)"
        ]);
    }

    public function getWeaponType(): string{
        return "supreme";
    }

    public function getDamageInfo(): DamageInfo{
        return new DamageInfo([
            "increase" => [
                "default" => 0.2
            ],
            "decrease" => [
                "default" => 0.1
            ]
        ]);
    }
}