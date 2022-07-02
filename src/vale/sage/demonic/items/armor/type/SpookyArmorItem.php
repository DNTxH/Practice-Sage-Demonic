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


/**
 * Class SpookyArmorItem
 * @package vale\sage\demonic\items\armor\type
 * @author Jibix
 * @date 07.01.2022 - 01:26
 * @project Genesis
 */
class SpookyArmorItem extends BaseArmorItem{

    public function __construct(ItemIdentifier $identifier){
        parent::__construct($identifier);
        self::addEnchantment(new EnchantmentInstance(VanillaEnchantments::PROTECTION(), 4));
        self::addEnchantment(new EnchantmentInstance(VanillaEnchantments::UNBREAKING(), 3));
        self::setCustomName(match ($this->getArmorType()) {
            "helmet" => "§l§6Spooky Hood",
            "chestplate" => "§l§6Spooky Chestplate",
            "leggings" => "§l§6Spooky Leggings",
            "boots" => "§l§6Spooky Boots",
        });
        self::setLore([
            "§r",
            "§l§6Spooky SET BONUS",
            "§r§6* Deal +20% more damage to all enemies.",
            "§r§6* Take -20% less damage from enemies",
            "§r§6* Halloweenify passive ability",
            "§r§7(Requires all 4 spooky items.)"
        ]);
    }

    public function getArmorName(): string{
        return "spooky";
    }

    public function getColoredName(): string{
        return "§6" . ucwords($this->getArmorName());
    }

    public function getDamageInfo(): DamageInfo{
        return new DamageInfo([
            "increase" => [
                "default" => 0.2
            ],
            "decrease" => [
                "default" => 0.25
            ]
        ]);
    }

    public function defend(EntityDamageEvent $event): void{
        parent::defend($event);
        if ($event instanceof EntityDamageByEntityEvent) {
            AbilityManager::getInstance()->getAbility("spooky")?->attemptReact($event->getEntity(), [$event->getDamager()]);
        }
    }
}