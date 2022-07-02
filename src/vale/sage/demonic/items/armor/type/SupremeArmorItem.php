<?php
namespace vale\sage\demonic\items\armor\type;
use vale\sage\demonic\items\armor\BaseArmorItem;
use vale\sage\demonic\items\DamageInfo;
use vale\sage\demonic\utils\Utils;
use pocketmine\entity\effect\EffectInstance;
use pocketmine\entity\effect\VanillaEffects;
use pocketmine\entity\Living;
use pocketmine\entity\projectile\Arrow;
use pocketmine\event\entity\EntityDamageByEntityEvent;
use pocketmine\event\entity\EntityDamageEvent;
use pocketmine\item\ArmorTypeInfo;
use pocketmine\item\enchantment\EnchantmentInstance;
use pocketmine\item\enchantment\VanillaEnchantments;
use pocketmine\item\ItemIdentifier;


/**
 * Class SupremeArmorItem
 * @package vale\sage\demonic\items\armor\type
 * @author Jibix
 * @date 06.01.2022 - 18:19
 * @project Genesis
 */
class SupremeArmorItem extends BaseArmorItem{

    public function __construct(ItemIdentifier $identifier){
        parent::__construct($identifier);
        self::addEnchantment(new EnchantmentInstance(VanillaEnchantments::PROTECTION(), 4));
        self::addEnchantment(new EnchantmentInstance(VanillaEnchantments::UNBREAKING(), 3));
        self::setCustomName(match ($this->getArmorType()) {
            "helmet" => "§l§4Supreme Headgear",
            "chestplate" => "§l§4Supreme Vest",
            "leggings" => "§l§4Supreme Chaps",
            "boots" => "§l§4Supreme Thruster Boots",
        });
        self::setLore([
            "§r",
            "§l§4SUPREME SET BONUS",
            "§r§4* §r§4No Fall Damage / Food Loss",
            "§r§4* §r§4Deal +15% damage to all enemies",
            "§r§4* §r§4Enemy arrows deal +10% more damage to you",
            "§r§4* §r§4+200% clout",
            "§r§4* §r§4Chance to give Slowness I for 5s",
            " §4when hitting an enemy from behind",
            "§r§7(Requires all 4 supreme items.)"
        ]);
    }

    public function getArmorName(): string{
        return "supreme";
    }

    public function getColoredName(): string{
        return "§4" . ucwords($this->getArmorName());
    }

    public function getDamageInfo(): DamageInfo{
        return new DamageInfo([
            "increase" => [
                "default" => 0.15
            ],
            "decrease" => [
                Arrow::class => 0.10
            ]
        ]);
    }

    public function attack(EntityDamageEvent $event): void{
        parent::attack($event);

        $entity = $event->getEntity();
        if ($event instanceof EntityDamageByEntityEvent) {
            if ($event->getDamager()->getDirectionVector()->dot($entity->getDirectionVector()) > 0) {
                if (Utils::getRandomFloat(0, 100) <= 5) {
                    if ($entity instanceof Living) {
                        $entity->getEffects()->add(new EffectInstance(VanillaEffects::SLOWNESS(), 5));
                    }
                }
            }
        }
    }

    public function defend(EntityDamageEvent $event): void{
        if ($event->getCause() == $event::CAUSE_FALL) {
            $event->cancel();
            return;
        }
        parent::defend($event);
    }
}