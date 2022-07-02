<?php
namespace vale\sage\demonic\items\armor\type;
use vale\sage\demonic\items\armor\BaseArmorItem;
use vale\sage\demonic\items\DamageInfo;
use pocketmine\item\enchantment\EnchantmentInstance;
use pocketmine\item\enchantment\VanillaEnchantments;
use pocketmine\item\ItemIdentifier;


/**
 * Class CupidArmorItem
 * @package vale\sage\demonic\items\armor\type
 * @author Jibix
 * @date 06.01.2022 - 23:27
 * @project Genesis
 */
class CupidArmorItem extends BaseArmorItem{

    public function __construct(ItemIdentifier $identifier){
        parent::__construct($identifier);
        self::addEnchantment(new EnchantmentInstance(VanillaEnchantments::PROTECTION(), 4));
        self::addEnchantment(new EnchantmentInstance(VanillaEnchantments::UNBREAKING(), 3));
        self::setCustomName(match ($this->getArmorType()) {
            "helmet" => "§l§dCupid Helmet",
            "chestplate" => "§l§dCupid Chestplate",
            "leggings" => "§l§dCupid Leggings",
            "boots" => "§l§dCupid Boots",
        });
        self::setLore([
            "§r",
            "§l§dCUPID SET BONUS",
            "§r§d* Deal +30% more damage to all enemies.",
            "§r§d* Take -15% less damage from enemies ",
            "§r§d* 58% less combat tag duration",
            "§r§d* Cupid Bow Teleportation Passive Ability",
            "§r§7(Requires all 4 cupid items.)"
        ]);
    }

    public function getArmorName(): string{
        return "cupid";
    }

    public function getColoredName(): string{
        return "§d" . ucwords($this->getArmorName());
    }

    public function getDamageInfo(): DamageInfo{
        return new DamageInfo([
            "increase" => [
                "default" => 0.3
            ],
            "decrease" => [
                "default" => 0.2
            ]
        ]);
    }
}