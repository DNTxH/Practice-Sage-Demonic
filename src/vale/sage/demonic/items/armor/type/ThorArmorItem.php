<?php
namespace vale\sage\demonic\items\armor\type;
use vale\sage\demonic\items\armor\BaseArmorItem;
use vale\sage\demonic\items\DamageInfo;
use vale\sage\demonic\manager\AbilityManager;
use pocketmine\event\entity\EntityDamageByEntityEvent;
use pocketmine\event\entity\EntityDamageEvent;
use pocketmine\item\ArmorTypeInfo;
use pocketmine\item\enchantment\EnchantmentInstance;
use pocketmine\item\enchantment\VanillaEnchantments;
use pocketmine\item\ItemIdentifier;
use pocketmine\player\Player;


/**
 * Class ThorArmorItem
 * @package vale\sage\demonic\items\armor\type
 * @author Jibix
 * @date 07.01.2022 - 03:45
 * @project Genesis
 */
class ThorArmorItem extends BaseArmorItem{

    public function __construct(ItemIdentifier $identifier){
        parent::__construct($identifier);
        self::addEnchantment(new EnchantmentInstance(VanillaEnchantments::PROTECTION(), 4));
        self::addEnchantment(new EnchantmentInstance(VanillaEnchantments::UNBREAKING(), 3));
        self::setCustomName(match ($this->getArmorType()) {
            "helmet" => "§l§bThor Helmet",
            "chestplate" => "§l§bThor Chestplate",
            "leggings" => "§l§bThor Leggings",
            "boots" => "§l§bThor Boots",
        });
        self::setLore([
            "§r",
            "§l§bThor SET BONUS",
            "§r§b* Take -15% less damage from enemies",
            "§r§b* 25% less combat tag duration",
            "§r§b* Mjolnir Passive Ability",
            "§r§7(Requires all 4 thor items.)"
        ]);
    }

    public function getArmorName(): string{
        return "thor";
    }

    public function getColoredName(): string{
        return "§b" . ucwords($this->getArmorName());
    }

    public function getDamageInfo(): DamageInfo{
        return new DamageInfo([
            "increase" => [
                "default" => 0.15
            ],
            "decrease" => [
                "default" => 0.25
            ]
        ]);
    }

    public function defend(EntityDamageEvent $event): void{
        parent::defend($event);
        if ($event instanceof EntityDamageByEntityEvent && $event->getEntity() instanceof Player) {
            AbilityManager::getInstance()->getAbility($this->getArmorName())?->attemptReact($event->getEntity());
        }
    }
}