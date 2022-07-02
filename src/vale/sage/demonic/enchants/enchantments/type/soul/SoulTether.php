<?php

declare(strict_types = 1);

namespace vale\sage\demonic\enchants\enchantments\type\soul;

use pocketmine\entity\effect\Effect;
use pocketmine\event\entity\EntityDamageByEntityEvent;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerJumpEvent;
use pocketmine\event\player\PlayerMoveEvent;
use pocketmine\item\enchantment\ItemFlags;
use pocketmine\player\Player;
use pocketmine\scheduler\ClosureTask;
use pocketmine\utils\TextFormat;
use vale\sage\demonic\enchants\CustomEnchant;
use vale\sage\demonic\enchants\CustomEnchantIds;
use vale\sage\demonic\enchants\enchantments\EnchantmentsManager;
use vale\sage\demonic\enchants\utils\IndicatorManager;
use vale\sage\demonic\enchants\utils\SoulPoint;
use vale\sage\demonic\Loader;

class SoulTether extends CustomEnchant implements Listener {

    /** @var array */
    private static array $tethered = [];

    public function __construct() {
        Loader::getInstance()->getServer()->getPluginManager()->registerEvents($this, Loader::getInstance());

        parent::__construct(
            "Soul Tether",
            CustomEnchantIds::SOULTETHER,
            "Active soul enchant, Chance to 'Tether' an enemy to you for 7s, affected player takes additional DMG the further away they are from you, up to 1.5x, any damage you receive from the tethered enemy is mirrored, When you do not have an active tether, +2% outgoing damage",
            3,
            ItemFlags::AXE,
            self::SOUL,
            self::OFFENSIVE,
            self::ENTITY_DAMAGE_BY_ENTITY,
            self::AXE
        );

        $this->callable = function (EntityDamageByEntityEvent $event, int $level) : void {
            $entity = $event->getEntity();
            $damager = $event->getDamager();
            $has = false;

            if ($event->isCancelled() || !$entity instanceof Player || !$damager instanceof Player) return;
            if (EnchantmentsManager::isSoulTrapped($entity)) return;
            if(isset(self::$tethered[$entity->getUniqueId()->toString()])) return;

            $content = $entity->getInventory()->getItemInHand();

            if(SoulPoint::hasTracker($content)) {
                if(SoulPoint::getSoul($content) >= 100) {
                    $entity->getInventory()->setItem($entity->getInventory()->getHeldItemIndex(), SoulPoint::setSoul($content, SoulPoint::getSoul($content) - 100));
                    $has = true;
                }
            }

            if($has) {
                self::$tethered[$entity->getUniqueId()->toString()] = $damager->getUniqueId()->toString();
                IndicatorManager::addTag($damager, $entity, "*SOUL TETHERED*", 7, TextFormat::GOLD);

                Loader::getInstance()->getScheduler()->scheduleDelayedTask(new ClosureTask(function () use($entity) : void {
                    unset(self::$tethered[$entity->getUniqueId()->toString()]);
                }), 140);
            }

            if(in_array($damager->getUniqueId()->toString(), self::$tethered)) $event->setBaseDamage($event->getBaseDamage() * 1.02);
        };
    }

    public function onDamage(EntityDamageByEntityEvent $event) : void {
        $entity = $event->getEntity();
        $damager = $event->getDamager();
        $multi = 1;

        if ($event->isCancelled() || !$entity instanceof Player || !$damager instanceof Player) return;

        if(isset(self::$tethered[$entity->getUniqueId()->toString()]) && Loader::getInstance()->getServer()->getPlayerByRawUUID(self::$tethered[$entity->getUniqueId()->toString()]) !== null) {
            $damager = Loader::getInstance()->getServer()->getPlayerByRawUUID(self::$tethered[$entity->getUniqueId()->toString()]);

            if($entity->getPosition()->distance($damager->getPosition()) <= 10) {
                $multi = 1.1;
            } elseif($entity->getPosition()->distance($damager->getPosition()) <= 20) {
                $multi = 1.2;
            } elseif($entity->getPosition()->distance($damager->getPosition()) <= 30) {
                $multi = 1.3;
            } elseif($entity->getPosition()->distance($damager->getPosition()) <= 40) {
                $multi = 1.4;
            } else {
                if($entity->getPosition()->distance($damager->getPosition()) <= 50) {
                    $multi = 1.5;
                }
            }

            $event->setBaseDamage($event->getBaseDamage() * $multi);

            $damager = $event->getDamager();
        }

        if(isset(self::$tethered[$damager->getUniqueId()->toString()]) && self::$tethered[$damager->getUniqueId()->toString()] === $entity->getUniqueId()->toString()) {
            $damager->setHealth($damager->getHealth() - $event->getFinalDamage());
        }
    }
}