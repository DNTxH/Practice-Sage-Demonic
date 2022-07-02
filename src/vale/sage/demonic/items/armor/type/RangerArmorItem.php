<?php
namespace vale\sage\demonic\items\armor\type;
use vale\sage\demonic\items\armor\BaseArmorItem;
use vale\sage\demonic\items\DamageInfo;
use pocketmine\entity\projectile\Arrow;
use pocketmine\event\entity\EntityDamageByChildEntityEvent;
use pocketmine\event\entity\EntityDamageEvent;
use pocketmine\item\ArmorTypeInfo;
use pocketmine\item\enchantment\EnchantmentInstance;
use pocketmine\item\enchantment\VanillaEnchantments;
use pocketmine\item\ItemIdentifier;


/**
 * Class RangerArmorItem
 * @package vale\sage\demonic\items\armor\type
 * @author Jibix
 * @date 06.01.2022 - 18:08
 * @project Genesis
 */
class RangerArmorItem extends BaseArmorItem{

    public function __construct(ItemIdentifier $identifier){
        parent::__construct($identifier);
        self::addEnchantment(new EnchantmentInstance(VanillaEnchantments::PROTECTION(), 4));
        self::addEnchantment(new EnchantmentInstance(VanillaEnchantments::UNBREAKING(), 3));
        self::setCustomName(match ($this->getArmorType()) {
            "helmet" => "§l§aRanger Hood",
            "chestplate" => "§l§cPhantom Shroud",
            "leggings" => "§l§cPhantom Robeset",
            "boots" => "§l§cPhantom Sandals",
        });
        self::setLore([
            "§r",
            "§l§aRANGER SET BONUS",
            "§r§a* §r§aEnemies bows do -25% less damage to you.",
            "§r§a* §r§aRanger bow grants +30% increased bow damage.",
            "§r§7(Requires all 4 ranger items.)"
        ]);
    }

    public function getArmorName(): string{
        return "ranger";
    }

    public function getColoredName(): string{
        return "§a" . ucwords($this->getArmorName());
    }

    public function getDamageInfo(): DamageInfo{
        return new DamageInfo([
            "decrease" => [
                Arrow::class => 0.25
            ]
        ]);
    }

    public function defend(EntityDamageEvent $event): void{
        if ($event instanceof EntityDamageByChildEntityEvent) {
            parent::defend($event);
        }
    }
}