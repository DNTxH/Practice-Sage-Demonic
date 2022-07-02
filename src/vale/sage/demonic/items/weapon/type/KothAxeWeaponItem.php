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
 * Class KothAxeWeaponItem
 * @package vale\sage\demonic\items\weapon\type
 * @author Jibix
 * @date 06.01.2022 - 20:02
 * @project Genesis
 */
class KothAxeWeaponItem extends BaseWeaponItem{

    public function __construct(ItemIdentifier $identifier){
        parent::__construct($identifier);
        self::addEnchantment(new EnchantmentInstance(VanillaEnchantments::SHARPNESS(), 5));
        self::addEnchantment(new EnchantmentInstance(VanillaEnchantments::UNBREAKING(), 3));
        self::setCustomName("§l§f§k|§r §l§cK§6.§eO§a.§bT§5.§dH §f§k|§r §l§fAxe");
        self::setLore([
            "§r",
            "§l§cK§6.§eO§a.§bT§5.§dH §fWEAPON BONUS",
            "§r§f* §r§fDeal +50% Durability Damage.",
            "§r§7(Requires all 4 koth items.)"
        ]);
    }

    public function getWeaponType(): string{
        return "koth";
    }

    public function getDamageInfo(): DamageInfo{
        return new DamageInfo([]);
    }

    public function attack(EntityDamageEvent $event): void{
        $this->damageArmor(($event->getFinalDamage() * 1.5), $event->getEntity());
    }
}