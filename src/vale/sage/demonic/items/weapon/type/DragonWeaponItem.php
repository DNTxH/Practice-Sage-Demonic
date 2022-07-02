<?php
namespace vale\sage\demonic\items\weapon\type;
use vale\sage\demonic\items\weapon\BaseWeaponItem;
use vale\sage\demonic\items\DamageInfo;
use pocketmine\item\enchantment\EnchantmentInstance;
use pocketmine\item\enchantment\VanillaEnchantments;
use pocketmine\item\ItemIdentifier;


/**
 * Class DragonWeaponItem
 * @package vale\sage\demonic\items\weapon\type
 * @author Jibix
 * @date 07.01.2022 - 02:11
 * @project Genesis
 */
class DragonWeaponItem extends BaseWeaponItem{

    public function __construct(ItemIdentifier $identifier){
        parent::__construct($identifier);
        self::addEnchantment(new EnchantmentInstance(VanillaEnchantments::SHARPNESS(), 5));
        self::addEnchantment(new EnchantmentInstance(VanillaEnchantments::UNBREAKING(), 3));
        self::setCustomName("§l§eDragon Sword");
        self::setLore([
            "§r",
            "§l§eDRAGON WEAPON BONUS",
            "§r§e* Deal +20% damage to all enemies.",
            "§r§7(Requires all 4 supreme items.)"
        ]);
    }

    public function getWeaponType(): string{
        return "dragon";
    }

    public function getDamageInfo(): DamageInfo{
        return new DamageInfo([
            "increase" => [
                "default" => 0.2
            ]
        ]);
    }
}