<?php
namespace vale\sage\demonic\items\weapon\type;
use vale\sage\demonic\items\weapon\BaseWeaponItem;
use vale\sage\demonic\items\DamageInfo;
use vale\sage\demonic\manager\AbilityManager;
use pocketmine\entity\projectile\Arrow;
use pocketmine\event\entity\EntityDamageByEntityEvent;
use pocketmine\event\entity\EntityDamageEvent;
use pocketmine\item\enchantment\EnchantmentInstance;
use pocketmine\item\enchantment\VanillaEnchantments;
use pocketmine\item\ItemIdentifier;


/**
 * Class CupidWeaponItem
 * @package vale\sage\demonic\items\weapon\type
 * @author Jibix
 * @date 07.01.2022 - 01:42
 * @project Genesis
 */
class CupidWeaponItem extends BaseWeaponItem{

    public function __construct(ItemIdentifier $identifier){
        parent::__construct($identifier);
        self::addEnchantment(new EnchantmentInstance(VanillaEnchantments::SHARPNESS(), 5));
        self::addEnchantment(new EnchantmentInstance(VanillaEnchantments::UNBREAKING(), 3));
        self::setCustomName("§l§dCupid Bow");
        self::setLore([
            "§r",
            "§l§dCUPID WEAPON BONUS",
            "§r§d* Deal +50% Durability Damage.",
            "§r§7(Requires all 4 cupid items.)"
        ]);
    }

    public function getWeaponType(): string{
        return "cupid";
    }

    public function getDamageInfo(): DamageInfo{
        return new DamageInfo([]);
    }

    public function attack(EntityDamageEvent $event): void{
        if ($event instanceof EntityDamageByEntityEvent) {
            if ($event->getDamager() instanceof Arrow) {
                $event->setBaseDamage($event->getFinalDamage() +10);
                AbilityManager::getInstance()->getAbility("cupid")?->attemptReact($event->getDamager()->getOwningEntity(), [$event->getEntity()->getPosition()]);
            }
        }
    }
}