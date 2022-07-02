<?php
namespace vale\sage\demonic\items\weapon\type;
use vale\sage\demonic\items\weapon\BaseWeaponItem;
use vale\sage\demonic\items\DamageInfo;
use pocketmine\entity\Human;
use pocketmine\event\entity\EntityDamageEvent;
use pocketmine\item\enchantment\EnchantmentInstance;
use pocketmine\item\enchantment\VanillaEnchantments;
use pocketmine\item\ItemIdentifier;


/**
 * Class YijkiWeaponItem
 * @package vale\sage\demonic\items\weapon\type
 * @author Jibix
 * @date 06.01.2022 - 20:53
 * @project Genesis
 */
class YijkiWeaponItem extends BaseWeaponItem{

    public function __construct(ItemIdentifier $identifier){
        parent::__construct($identifier);
        self::addEnchantment(new EnchantmentInstance(VanillaEnchantments::SHARPNESS(), 5));
        self::addEnchantment(new EnchantmentInstance(VanillaEnchantments::UNBREAKING(), 3));
        self::setCustomName("§l§fYijki's World Ender");
        self::setLore([
            "§r",
            "§l§fYIJKI WEAPON BONUS",
            "§r§f* §r§fDeal +20% damage to all enemies.",
            "§r§f* §r§f+125% Revenge of yijki Ability",
            "§r§7(Requires all 4 yijki items.)"
        ]);
    }

    public function getWeaponType(): string{
        return "yijki";
    }

    public function getDamageInfo(): DamageInfo{
        return new DamageInfo([
            "increase" => [
                "default" => 0.2
            ]
        ]);
    }
}