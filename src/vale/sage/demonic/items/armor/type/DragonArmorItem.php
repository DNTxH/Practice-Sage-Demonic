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
 * Class DragonArmorItem
 * @package vale\sage\demonic\items\armor\type
 * @author Jibix
 * @date 06.01.2022 - 18:00
 * @project Genesis
 */
class DragonArmorItem extends BaseArmorItem{

    public function __construct(ItemIdentifier $identifier){
        parent::__construct($identifier);
        self::addEnchantment(new EnchantmentInstance(VanillaEnchantments::PROTECTION(), 4));
        self::addEnchantment(new EnchantmentInstance(VanillaEnchantments::UNBREAKING(), 3));
        self::setCustomName(match ($this->getArmorType()) {
            "helmet" => "§l§eDecapitated Dragon Skull",
            "chestplate" => "§l§eFiery Chestplate of Dragons",
            "leggings" => "§l§eScorched Leggings of Dragons",
            "boots" => "§l§eDragon Slayer Battle Boots",
        });
        self::setLore([
            "§r",
            "§l§eDRAGON SET BONUS",
            "§r§e* +15% PvP Damage",
            "§r§e* Take -20% less damage from enemies",
            "§r§7(Requires all 4 dragon items.)"
        ]);
    }

    public function getArmorName(): string{
        return "dragon";
    }

    public function getColoredName(): string{
        return "§e" . ucwords($this->getArmorName());
    }

    public function getDamageInfo(): DamageInfo{
        return new DamageInfo([
            "increase" => [
                Player::class => 0.15
            ],
            "decrease" => [
                "default" => 0.2
            ]
        ]);
    }
}