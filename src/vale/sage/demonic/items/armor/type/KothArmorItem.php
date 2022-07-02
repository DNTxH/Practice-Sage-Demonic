<?php
namespace vale\sage\demonic\items\armor\type;
use vale\sage\demonic\items\armor\BaseArmorItem;
use vale\sage\demonic\items\DamageInfo;
use pocketmine\event\entity\EntityDamageEvent;
use pocketmine\item\ArmorTypeInfo;
use pocketmine\item\enchantment\EnchantmentInstance;
use pocketmine\item\enchantment\VanillaEnchantments;
use pocketmine\item\ItemIdentifier;
use pocketmine\player\Player;


/**
 * Class KothArmorItem
 * @package vale\sage\demonic\items\armor\type
 * @author Jibix
 * @date 06.01.2022 - 18:01
 * @project Genesis
 */
class KothArmorItem extends BaseArmorItem{

    public function __construct(ItemIdentifier $identifier){
        parent::__construct($identifier);
        self::addEnchantment(new EnchantmentInstance(VanillaEnchantments::PROTECTION(), 4));
        self::addEnchantment(new EnchantmentInstance(VanillaEnchantments::UNBREAKING(), 3));
        self::setCustomName(match ($this->getArmorType()) {
            'helmet' => "§l§f§k|§r §l§cK§6.§eO§a.§bT§5.§dH §f§k|§r §l§fHelmet",
            'chestplate' => "§l§f§k|§r §l§cK§6.§eO§a.§bT§5.§dH §f§k|§r §l§fChestplate",
            'leggings' => "§l§f§k|§r §l§cK§6.§eO§a.§bT§5.§dH §f§k|§r §l§fLeggings",
            "boots" => "§l§f§k|§r §l§cK§6.§eO§a.§bT§5.§dH §f§k|§r §l§fBoots"
        });
        self::setLore([
            '§r',
            '§l§dKOTH SET BONUS',
            '§l§d* §r§d+20% PvP Damage',
            '§l§d* §r§d+50% PvE Damage',
            '§l§d* §r§dNo Fall Damage',
            '§r§7(Requires all 4 koth items.)'
        ]);
    }

    public function getArmorName(): string{
        return "koth";
    }

    public function getColoredName(): string{
        return "§l§f§k|§r §l§cK§6.§eO§a.§bT§5.§dH§r";
    }

    public function getDamageInfo(): DamageInfo{
        return new DamageInfo([
            "increase" => [
                Player::class => 0.2,
                "default" => 0.5
            ],
            "decrease" => [
                "default" => 0.2
            ]
        ]);
    }

    public function defend(EntityDamageEvent $event): void{
        if ($event->getCause() == $event::CAUSE_FALL) {
            $event->cancel();
            return;
        }
        parent::defend($event);
    }
}