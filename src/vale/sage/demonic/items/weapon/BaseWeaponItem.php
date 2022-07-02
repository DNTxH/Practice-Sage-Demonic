<?php
namespace vale\sage\demonic\items\weapon;
use vale\sage\demonic\items\DamageInfo;
use pocketmine\entity\Human;
use pocketmine\event\entity\EntityDamageEvent;
use pocketmine\item\Armor;
use pocketmine\item\Durable;
use pocketmine\item\enchantment\EnchantmentInstance;
use pocketmine\item\Item;
use pocketmine\item\ItemFactory;
use pocketmine\item\ItemIdentifier;
use pocketmine\world\sound\ItemBreakSound;


/**
 * Class BaseWeaponItem
 * @package vale\sage\demonic\items\weapon
 * @author Jibix
 * @date 06.01.2022 - 17:20
 * @project Genesis
 */
abstract class BaseWeaponItem{

    protected Item $item;

    public function __construct(ItemIdentifier $identifier){
        $this->item = ItemFactory::getInstance()->get($identifier->getId(), $identifier->getMeta());
        $tag = $this->item->getNamedTag()->setString("customWeapon", $this->getWeaponType());
        $this->item->setNamedTag($tag);
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

    abstract public function getWeaponType(): string;
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

    public function damageArmor(float $damage, Human $entity) : void{
        $durabilityRemoved = (int) max(floor($damage / 4), 1);

        $armor = $entity->getArmorInventory()->getContents(true);
        foreach ($armor as $item){
            if ($item instanceof Armor){
                $this->damageItem($item, $durabilityRemoved, $entity);
            }
        }

        $entity->getArmorInventory()->setContents($armor);
    }

    public static function damageItem(Durable $item, int $durabilityRemoved, Human $entity) : void{
        $item->applyDamage($durabilityRemoved);
        if ($item->isBroken()) {
            $entity->broadcastSound(new ItemBreakSound());
        }
    }
}