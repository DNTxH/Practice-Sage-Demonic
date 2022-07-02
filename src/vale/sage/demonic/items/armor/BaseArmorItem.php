<?php
namespace vale\sage\demonic\items\armor;
use vale\sage\demonic\items\custom\CustomItemProperties;
use vale\sage\demonic\items\DamageInfo;
use vale\sage\demonic\utils\Utils;
use pocketmine\event\entity\EntityDamageEvent;
use pocketmine\inventory\ArmorInventory;
use pocketmine\item\Armor;
use pocketmine\item\ArmorTypeInfo;
use pocketmine\item\enchantment\EnchantmentInstance;
use pocketmine\item\Item;
use pocketmine\item\ItemFactory;
use pocketmine\item\ItemIdentifier;
use pocketmine\player\Player;


/**
 * Class BaseArmorItem
 * @package CustomSets\item\armor
 * @author Jibix
 * @date 06.01.2022 - 17:02
 * @project Genesis
 */
abstract class BaseArmorItem{

    protected Item $item;

    public function __construct(ItemIdentifier $identifier){
        $this->item = ItemFactory::getInstance()->get($identifier->getId(), $identifier->getMeta());
        $tag = $this->item->getNamedTag()->setString("customArmor", $this->getArmorName());
        $this->item->setNamedTag($tag);
    }

    public function getArmorType(): string{
        if (!$this->item instanceof Armor) return "undefined";
        return Utils::armorSlotToType($this->item->getArmorSlot());
    }

    public function addEnchantment(EnchantmentInstance $instance): void{
        $this->item->addEnchantment($instance);
    }
    
    public function setCustomName(string $customName): void{
        $this->item->setCustomName($customName);
    }
    
    public function setLore(array $lore): void{
        $this->item->setLore($lore);
    }
    
    public function asItem(): Item{
        return $this->item;
    }

    abstract public function getArmorName(): string;
    abstract public function getColoredName(): string;
    abstract public function getDamageInfo(): DamageInfo;

    public function attack(EntityDamageEvent $event): void{
        $info = $this->getDamageInfo();
        if ($info->getIncrease($event->getEntity()::class) > 0) {
            $event->setModifier(($event->getFinalDamage() * $info->getIncrease($event->getEntity())), DamageInfo::CUSTOM_MODIFIER);
        }
    }

    public function defend(EntityDamageEvent $event): void{
        $info = $this->getDamageInfo();
        if ($info->getDecrease($event->getEntity()::class) > 0) {
            $event->setModifier(-($event->getFinalDamage() * $info->getDecrease($event->getEntity())), DamageInfo::CUSTOM_MODIFIER);
        }
    }

    public function applyFullArmor(Player $player): void{}
}