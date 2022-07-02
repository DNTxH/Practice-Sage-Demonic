<?php
namespace vale\sage\demonic\items\armor\type;
use vale\sage\demonic\items\armor\BaseArmorItem;
use vale\sage\demonic\items\DamageInfo;
use vale\sage\demonic\manager\AbilityManager;
use vale\sage\demonic\utils\Utils;
use pocketmine\entity\effect\VanillaEffects;
use pocketmine\event\entity\EntityDamageEvent;
use pocketmine\item\ArmorTypeInfo;
use pocketmine\item\enchantment\EnchantmentInstance;
use pocketmine\item\enchantment\VanillaEnchantments;
use pocketmine\item\ItemIdentifier;
use pocketmine\player\Player;


/**
 * Class FantasyArmorItem
 * @package vale\sage\demonic\items\armor\type
 * @author Jibix
 * @date 06.01.2022 - 21:09
 * @project Genesis
 */
class FantasyArmorItem extends BaseArmorItem{

    public function __construct(ItemIdentifier $identifier){
        parent::__construct($identifier);
        self::addEnchantment(new EnchantmentInstance(VanillaEnchantments::PROTECTION(), 4));
        self::addEnchantment(new EnchantmentInstance(VanillaEnchantments::UNBREAKING(), 3));
        self::setCustomName(match ($this->getArmorType()) {
            "helmet" => "§l§2Fantasy Helmet",
            "chestplate" => "§l§2Fantasy Chestplate",
            "leggings" => "§l§2Fantasy Leggings",
            "boots" => "§l§2Fantasy Boots",
        });
        self::setLore([
            "§r",
            "§l§2Fantasy SET BONUS",
            "§r§2* Gears IV",
            "§r§2* Deal +25% more damage to all enemies.",
            "§r§2* 10% Critical Strike Chance",
            "§r§2* Fantasy Trap Passive Ability",
            "§r§7(Requires all 4 fantasy items.)"
        ]);
    }

    public function getArmorName(): string{
        return "fantasy";
    }

    public function getColoredName(): string{
        return "§2" . ucwords($this->getArmorName());
    }

    public function getDamageInfo(): DamageInfo{
        return new DamageInfo([
            "increase" => [
                "default" => 0.25
            ]
        ]);
    }

    public function applyFullArmor(Player $player): void{
        $player->setMovementSpeed($player->getMovementSpeed() * (1 + 0.2 * 4));
    }

    public function attack(EntityDamageEvent $event): void{
        parent::attack($event);
        AbilityManager::getInstance()->getAbility("fantasy")?->attack($event);
    }
}