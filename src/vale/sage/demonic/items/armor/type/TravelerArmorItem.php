<?php
namespace vale\sage\demonic\items\armor\type;
use vale\sage\demonic\items\armor\BaseArmorItem;
use vale\sage\demonic\items\DamageInfo;
use vale\sage\demonic\manager\AbilityManager;
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
use pocketmine\player\Player;


/**
 * Class TravelerArmorItem
 * @package vale\sage\demonic\items\armor\type
 * @author Jibix
 * @date 06.01.2022 - 18:25
 * @project Genesis
 */
class TravelerArmorItem extends BaseArmorItem{

    public function __construct(ItemIdentifier $identifier){
        parent::__construct($identifier);
        self::addEnchantment(new EnchantmentInstance(VanillaEnchantments::PROTECTION(), 4));
        self::addEnchantment(new EnchantmentInstance(VanillaEnchantments::UNBREAKING(), 3));
        self::setCustomName(match ($this->getArmorType()) {
            "helmet" => "§l§5Interdimensional Hood",
            "chestplate" => "§l§5Chestplate of Ad Infinitum",
            "leggings" => "§l§5Timeless Robes",
            "boots" => "§l§5Warp Speed Sandals",
        });
        self::setLore([
            "§r",
            "§l§5TRAVELER SET BONUS",
            "§r§5* §r§5You deal +30% more damage.",
            "§r§5* §r§5Dimensional Shift Passive Ability ",
            "§r§7(Requires all 4 dimensional traveler items.)"
        ]);
    }

    public function getArmorName(): string{
        return "traveler";
    }

    public function getColoredName(): string{
        return "§5" . ucwords($this->getArmorName());
    }

    public function getDamageInfo(): DamageInfo{
        return new DamageInfo([
            "increase" => [
                "default" => 0.3
            ],
            "decrease" => [
                Arrow::class => 0.1
            ]
        ]);
    }

    public function attack(EntityDamageEvent $event): void{
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

        parent::attack($event);
    }

    public function defend(EntityDamageEvent $event): void{
        $entity = $event->getEntity();
        if ($event instanceof EntityDamageByEntityEvent) {
            $attacker = $event->getDamager();
            if ($attacker instanceof Player && $entity instanceof Player) {
                AbilityManager::getInstance()->getAbility("traveler")?->attemptReact($entity);
            }
        }
    }
}