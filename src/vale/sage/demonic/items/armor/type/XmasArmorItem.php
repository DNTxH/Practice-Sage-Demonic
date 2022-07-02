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
 * Class XmasArmorItem
 * @package vale\sage\demonic\items\armor\type
 * @author Jibix
 * @date 06.01.2022 - 23:53
 * @project Genesis
 */
class XmasArmorItem extends BaseArmorItem{

    public function __construct(ItemIdentifier $identifier){
        parent::__construct($identifier);
        self::addEnchantment(new EnchantmentInstance(VanillaEnchantments::PROTECTION(), 4));
        self::addEnchantment(new EnchantmentInstance(VanillaEnchantments::UNBREAKING(), 3));
        self::setCustomName(match ($this->getArmorType()) {
            "helmet" => "§l§cX§2M§aA§fS Helmet",
            "chestplate" => "§l§cX§2M§aA§fS Chestplate",
            "leggings" => "§l§cX§2M§aA§fS Leggings",
            "boots" => "§l§cX§2M§aA§fS Boots",
        });
        self::setLore([
            "§r",
            "§l§l§cX§2M§aA§fS SET BONUS",
            "§r§2* Deal +20% more damage to all enemies.",
            "§r§2* Take -15% less damage from enemies",
            "§r§2* Active Snowify Ability",
            "§r§7(Requires all 4 xmas items.)"
        ]);
    }

    public function getArmorName(): string{
        return "xmas";
    }

    public function getColoredName(): string{
        return "§l§cX§2M§aA§fS§r";
    }

    public function getDamageInfo(): DamageInfo{
        return new DamageInfo([
            "increase" => [
                "default" => 0.2
            ],
            "decrease" => [
                "default" => 0.15
            ]
        ]);
    }

    public function defend(EntityDamageEvent $event): void{
        parent::defend($event);
        if ($event instanceof EntityDamageByEntityEvent && $event->getEntity() instanceof Player) {
            AbilityManager::getInstance()->getAbility("xmas")?->attemptReact($event->getEntity());
        }
    }
}