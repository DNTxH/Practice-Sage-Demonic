<?php
namespace vale\sage\demonic\items\armor\type;
use vale\sage\demonic\items\armor\BaseArmorItem;
use vale\sage\demonic\items\DamageInfo;
use vale\sage\demonic\manager\AbilityManager;
use pocketmine\event\entity\EntityDamageEvent;
use pocketmine\item\ArmorTypeInfo;
use pocketmine\item\enchantment\EnchantmentInstance;
use pocketmine\item\enchantment\VanillaEnchantments;
use pocketmine\item\ItemIdentifier;


/**
 * Class ReaperArmorItem
 * @package vale\sage\demonic\items\armor\type
 * @author Jibix
 * @date 06.01.2022 - 22:52
 * @project Genesis
 */
class ReaperArmorItem extends BaseArmorItem{

    public function __construct(ItemIdentifier $identifier){
        parent::__construct($identifier);
        self::addEnchantment(new EnchantmentInstance(VanillaEnchantments::PROTECTION(), 4));
        self::addEnchantment(new EnchantmentInstance(VanillaEnchantments::UNBREAKING(), 3));
        self::setCustomName(match ($this->getArmorType()) {
            "helmet" => "§l§4Reaper Helmet",
            "chestplate" => "§l§4Reaper Chestplate",
            "leggings" => "§l§4Reaper Leggings",
            "boots" => "§l§4Reaper Boots",
        });
        self::setLore([
            "§r",
            "§l§4Reaper SET BONUS",
            "§r§4* Deal +30% more damage to all enemies.",
            "§r§4* Take 15% less damage from enemies",
            "§r§4* Mark of the Reaper Passive Ability",
            "§r§7(Requires all 4 reaper items.)"
        ]);
    }

    public function getArmorName(): string{
        return "reaper";
    }

    public function getColoredName(): string{
        return "§4" . ucwords($this->getArmorName());
    }

    public function getDamageInfo(): DamageInfo{
        return new DamageInfo([
            "increase" => [
                "default" => 0.3
            ],
            "decrease" => [
                "default" => 0.15
            ]
        ]);
    }

    public function attack(EntityDamageEvent $event): void{
        parent::attack($event);
        AbilityManager::getInstance()->getAbility("reaper")?->attack($event);
    }
}