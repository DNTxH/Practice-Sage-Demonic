<?php

namespace vale\sage\demonic\commands\defaults\staff;

use vale\sage\demonic\Loader;
use pocketmine\block\BlueIce;
use pocketmine\event\entity\EntityDamageByEntityEvent;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerItemUseEvent;
use pocketmine\event\player\PlayerQuitEvent;
use pocketmine\item\Stick;
use pocketmine\permission\BanEntry;
use pocketmine\player\Player;

class StaffModeListener implements Listener {

    private array $itemUseCooldown = [];

    public function onUse(PlayerItemUseEvent $event) : void {
        $player = $event->getPlayer();
        if (Loader::getStaffManager()->isInStaffMode($player)) {
            if (isset($this->itemUseCooldown[$player->getName()])) {
                if (time() - $this->itemUseCooldown[$player->getName()] < 1) return;
            }
            $this->itemUseCooldown[$player->getName()] = time();
            $item = $event->getItem();
            if ($item instanceof Stick) {
                $players = Loader::getInstance()->getServer()->getOnlinePlayers();
                $chosen = $players[array_rand($players)];
                $player->teleport($chosen->getPosition());
                $player->sendMessage("§aYou have been warped to " . $chosen->getName());
            }
        }
    }

    public function onDamage(EntityDamageByEntityEvent $event) : void {
        $player = $event->getEntity();
        $damager = $event->getDamager();
        if ($player instanceof Player and $damager instanceof Player) {
            if (Loader::getStaffManager()->isInStaffMode($damager)) {
                if ($damager->getInventory()->getItemInHand()->getId() == -11) {
                    if (in_array($player->getName(), Loader::getStaffManager()->frozen)) {
                        unset(Loader::getStaffManager()->frozen[$player->getName()]);
                        $player->setImmobile(false);
                        $player->sendMessage("§aYou are no longer frozen.");
                        $damager->sendMessage("§aPlayer no longer frozen.");
                    } else {
                        Loader::getStaffManager()->frozen[] = $player->getName();
                        $player->setImmobile(true);
                        $player->sendMessage("§cYou have been frozen. §lDO NOT LOG OUT!");
                        $damager->sendMessage("§aFroze player.");
                    }
                }
            } else {
                if (in_array($player->getName(), Loader::getStaffManager()->frozen)) {
                    $event->cancel();
                    $damager->sendMessage("§cPlease do not attack frozen players.");
                }
            }
        }
    }

    public function onQuit(PlayerQuitEvent $event) : void {
        $player = $event->getPlayer();
        if (in_array($player->getName(), Loader::getStaffManager()->frozen)) {
            Loader::getInstance()->getServer()->getNameBans()->add(new BanEntry($player->getName()));
            Loader::getInstance()->getServer()->broadcastMessage("§l§c   \n§r§7[§dGenesis§7] §r§c". $player->getName() . " was banned for logging out whilst frozen.");
        }
        if (Loader::getStaffManager()->isInStaffMode($player)) {
            Loader::getStaffManager()->unsetFromStaffMode($player);
        }
    }

}