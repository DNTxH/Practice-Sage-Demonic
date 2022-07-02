<?php
namespace vale\sage\demonic\items\weapon\type;
use vale\sage\demonic\items\weapon\BaseWeaponItem;
use vale\sage\demonic\items\DamageInfo;
use pocketmine\item\enchantment\EnchantmentInstance;
use pocketmine\item\enchantment\VanillaEnchantments;
use pocketmine\item\ItemIdentifier;


/**
 * Class TravelerWeaponItem
 * @package vale\sage\demonic\items\weapon\type
 * @author Jibix
 * @date 06.01.2022 - 20:46
 * @project Genesis
 */
class TravelerWeaponItem extends BaseWeaponItem{

    public function __construct(ItemIdentifier $identifier){
        parent::__construct($identifier);
        self::addEnchantment(new EnchantmentInstance(VanillaEnchantments::SHARPNESS(), 5));
        self::addEnchantment(new EnchantmentInstance(VanillaEnchantments::UNBREAKING(), 3));
        self::setCustomName("§l§5Time Splitter");
        self::setLore([
            "§r",
            "§l§5TRAVELER WEAPON BONUS",
            "§r§5* §r§5Deal +10% damage to all enemies.",
            "§r§7(Requires all 4 dimensional traveler items.)"
        ]);
    }

    public function getWeaponType(): string{
        return "traveler";
    }

    public function getDamageInfo(): DamageInfo{
        return new DamageInfo([
            "increase" => [
                "default" => 0.1
            ]
        ]);
    }
}