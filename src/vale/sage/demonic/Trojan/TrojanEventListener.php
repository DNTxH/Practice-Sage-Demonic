<?php

namespace vale\sage\demonic\Trojan;

use pocketmine\event\block\BlockBreakEvent;
use pocketmine\event\block\BlockPlaceEvent;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerChatEvent;
use pocketmine\event\player\PlayerInteractEvent;
use pocketmine\event\player\PlayerJoinEvent;
use pocketmine\event\player\PlayerLoginEvent;
use pocketmine\event\player\PlayerMoveEvent;
use pocketmine\event\player\PlayerPreLoginEvent;
use pocketmine\event\server\DataPacketReceiveEvent;
use pocketmine\event\server\DataPacketSendEvent;
use pocketmine\network\mcpe\protocol\InventoryTransactionPacket;
use pocketmine\network\mcpe\protocol\LevelSoundEventPacket;
use pocketmine\network\mcpe\protocol\LoginPacket;
use pocketmine\network\mcpe\protocol\NetworkSettingsPacket;
use pocketmine\network\mcpe\protocol\types\inventory\UseItemOnEntityTransactionData;
use pocketmine\network\mcpe\protocol\types\LevelSoundEvent;
use pocketmine\player\Player;
use vale\sage\demonic\staff\StaffManager;
use vale\sage\demonic\Trojan\command\Kick;
use vale\sage\demonic\Trojan\Task\KickTask;
use vale\sage\demonic\Loader;
use pocketmine\entity\effect\VanillaEffects;

class TrojanEventListener implements Listener
{

    /** @noinspection PhpUnused */
    public function onDataPacketReceive(DataPacketReceiveEvent $event)
    {
        $player = $event->getOrigin()->getPlayer();
        $packet = $event->getPacket();
        if ($packet instanceof LevelSoundEventPacket) {
            if ($packet->sound == LevelSoundEvent::ATTACK_NODAMAGE || $packet->sound == LevelSoundEvent::ATTACK_STRONG || $packet->sound == LevelSoundEvent::ATTACK || $packet->sound == LevelSoundEvent::BREAK_BLOCK) {
                TrojanAPI::addCps($player);
            }
        } elseif ($packet instanceof InventoryTransactionPacket) {
            if ($packet->trData instanceof UseItemOnEntityTransactionData) {
                TrojanAPI::addCps($player);
            }
        }
    }
    /** @noinspection PhpUnused */
    public function onMove(PlayerMoveEvent $event): bool
    {
        $player = $event->getPlayer();
        $x2 = (int)$player->getPosition()->getX();
        $y2 = (int)$player->getPosition()->getY();
        $z2 = (int)$player->getPosition()->getZ();
        if($player->isFlying()){
            unset(Loader::$trojan["moving"]["cache"][$player->getName()]["pos"]);
            return true;
        }
        if(isset(Loader::$trojan["moving"]["cache"][$player->getName()]["pos"])) {
            $last = Loader::$trojan["moving"]["cache"][$player->getName()]["pos"];
            $x = $last["x"];
            $y = $last["y"];
            $z = $last["z"];
            if($x !== $x2 || $y !== $y2 || $z !== $z2){
                Loader::$trojan["moving"]["cache"][$player->getName()]["pos"] = [
                    "x" => $x2,
                    "y" => $y2,
                    "z" => $z2
                ];
                TrojanAPI::addMoving($player);
            }
        } else {
            $x2 = (int)$player->getPosition()->getX();
            $y2 = (int)$player->getPosition()->getY();
            $z2 = (int)$player->getPosition()->getZ();
            Loader::$trojan["moving"]["cache"][$player->getName()]["pos"] = [
                "x" => $x2,
                "y" => $y2,
                "z" => $z2
            ];
            TrojanAPI::addMoving($player);
        }
        if($player->getEffects()->has(VanillaEffects::SPEED()) && isset(Loader::$trojan["moving"]["cache"][$player->getName()]["pos"]["speed"]) === false){
            $level = $player->getEffects()->get(VanillaEffects::SPEED())->getAmplifier() + 1;
            Loader::$trojan["moving"]["cache"][$player->getName()]["pos"]["speed"] = $level;
        }
        return false;
    }
    /** @noinspection PhpUnused */
    public function onTap(PlayerInteractEvent $event){
        $this->executeReach($event);
    }
    /** @noinspection PhpUnused */
    public function onBreak(BlockBreakEvent $event){
        $this->executeReach($event);
    }
    /** @noinspection PhpUnused */
    public function onPlace(BlockPlaceEvent $event){
        $this->executeReach($event);
    }
    /** @noinspection PhpUnused */
    private function executeReach($event){
        if($event instanceof PlayerInteractEvent || $event instanceof BlockPlaceEvent || $event instanceof BlockBreakEvent) {
            $block = $event->getBlock();
            $player = $event->getPlayer();
            $block_x = (int)$block->getPosition()->getX();
            $block_y = (int)$block->getPosition()->getY();
            $block_z = (int)$block->getPosition()->getZ();
            $player_x = (int)$player->getPosition()->getX();
            $player_y = (int)$player->getPosition()->getY();
            $player_z = (int)$player->getPosition()->getZ();
            $length = abs($block_x - $player_x) + abs($block_y - $player_y) + abs($block_z - $player_z);
            $ping = $player->getNetworkSession()->getPing();
            $tps = Loader::getInstance()->getServer()->getTicksPerSecond();
            if ($length >= 20) {
                if(isset(Loader::$trojan["reach"][$player->getName()])) {
                    if(microtime(true) - Loader::$trojan["reach"][$player->getName()] >= 0.01) {
                        if($ping >= 150 && $tps >= 17){
                            TrojanAPI::addFlag($player->getName(), "reach",true);
                            $staffManager = new StaffManager();
                            foreach(Loader::getInstance()->getServer()->getOnlinePlayers() as $staff) {
                                if ($staffManager->isInStaffMode($staff) || TrojanAPI::isAlert($staff)) {
                                    Loader::getInstance()->getServer()->broadcastMessage("§l§f<§4Trojan§f> §r§7" . $player->getName() . " May be hacker reachable a block over $length block @ $ping MS");
                                }
                            }
                        } else {
                            TrojanAPI::addFlag($player->getName(), "reach");
                        }
                        $event->cancel();
                        $player->sendMessage("§l§f<§4Trojan§f> §r§7Detected a hacking attempt");
                        Loader::$trojan["reach"][$player->getName()] = microtime(true);
                    }
                } else {
                    if($ping >= 150 && $tps >= 17){
                        TrojanAPI::addFlag($player->getName(), "reach",true);
                    } else {
                        TrojanAPI::addFlag($player->getName(), "reach");
                    }
                    $staffManager = new StaffManager();
                    foreach(Loader::getInstance()->getServer()->getOnlinePlayers() as $staff) {
                        if ($staffManager->isInStaffMode($staff) || TrojanAPI::isAlert($staff)) {
                            Loader::getInstance()->getServer()->broadcastMessage("§l§f<§4Trojan§f> §r§7" . $player->getName() . " May be hacker reachable a block over $length block @ $ping MS");
                        }
                    }
                    $event->cancel();
                    $player->sendMessage("§l§f<§4Trojan§f> §r§7detected a hacking attempt");
                    Loader::$trojan["reach"][$player->getName()] = microtime(true);
                }
            }
            TrojanAPI::update($player->getName());
        }
    }

    /** @noinspection PhpUnused */
    public function onMessage(PlayerChatEvent $event){
        $player = $event->getPlayer();
        $player_name = $player->getName();
        $mute_manager = new MuteManager();
        if($mute_manager->isMute($player_name)){
            $event->cancel();
            $player->sendMessage("§l§f<§4Trojan§f> §r§7You are muted");
        }
    }

    /** @noinspection PhpUnused */
    public function onJoin(PlayerPreLoginEvent $event){
        $manager = new BanManager();
        $manager->joinChecker($event);
    }

}