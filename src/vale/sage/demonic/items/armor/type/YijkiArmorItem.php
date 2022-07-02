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
 * Class YijkiArmorItem
 * @package vale\sage\demonic\items\armor\type
 * @author Jibix
 * @date 06.01.2022 - 18:41
 * @project Genesis
 */
class YijkiArmorItem extends BaseArmorItem{

    public function __construct(ItemIdentifier $identifier){
        parent::__construct($identifier);
        self::addEnchantment(new EnchantmentInstance(VanillaEnchantments::PROTECTION(), 4));
        self::addEnchantment(new EnchantmentInstance(VanillaEnchantments::UNBREAKING(), 3));
        self::setCustomName(match ($this->getArmorType()) {
            "helmet" => "§r§l§fMask of Yijki the Destroyer of Worlds",
            "chestplate" => "§r§l§fMantle of Yijki the Destroyer of Worlds",
            "leggings" => "§r§l§fRobeset of Yijki the Destroyer of Worlds",
            "boots" => "§r§l§fFootwraps of Yijki the Destroyer of Worlds",
        });
        self::setLore([
            "§r",
            "§l§fYIJKI SET BONUS",
            "§r§f* §r§fEnemies deal -30% less damage to you.",
            "§r§f* §r§fRevenge Of Yijki Passive Ability",
            "§r§7(Requires all 4 yijki items.)"
        ]);
    }

    public function getArmorName(): string{
        return "yijki";
    }

    public function getColoredName(): string{
        return "§f" . ucwords($this->getArmorName());
    }

    public function getDamageInfo(): DamageInfo{
        return new DamageInfo([
            "decrease" => [
                "default" => 0.3
            ]
        ]);
    }

    public function defend(EntityDamageEvent $event): void{
        parent::defend($event);

        $entity = $event->getEntity();
        if ($event instanceof EntityDamageByEntityEvent) {
            $attacker = $event->getDamager();
            if ($attacker instanceof Player && $entity instanceof Player) {
                AbilityManager::getInstance()->getAbility("yijki")?->attemptReact($entity);
            }
        }
    }
}