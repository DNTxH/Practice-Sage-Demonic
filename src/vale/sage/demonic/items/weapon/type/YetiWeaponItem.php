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
 * Class YetiWeaponItem
 * @package vale\sage\demonic\items\weapon\type
 * @author Jibix
 * @date 06.01.2022 - 20:49
 * @project Genesis
 */
class YetiWeaponItem extends BaseWeaponItem{

    public function __construct(ItemIdentifier $identifier){
        parent::__construct($identifier);
        self::addEnchantment(new EnchantmentInstance(VanillaEnchantments::SHARPNESS(), 5));
        self::addEnchantment(new EnchantmentInstance(VanillaEnchantments::UNBREAKING(), 3));
        self::setCustomName("§l§bYeti Maul");
        self::setLore([
            "§r",
            "§l§bYETI WEAPON BONUS",
            "§r§b* Deal +75% durability damage.",
            "§r§b* Deal +7.5% damage to all enemies.",
            "§r§7(Requires all 4 yeti items.)"
        ]);
    }

    public function getWeaponType(): string{
        return "yeti";
    }

    public function getDamageInfo(): DamageInfo{
        return new DamageInfo([
            "increase" => [
                "default" => 0.075
            ]
        ]);
    }

    public function attack(EntityDamageEvent $event): void{
        parent::attack($event);
        $entity = $event->getEntity();
        if ($entity instanceof Human) {
            $this->damageArmor((($event->getFinalDamage() * 1.075) * 1.75), $entity);
        }
    }
}