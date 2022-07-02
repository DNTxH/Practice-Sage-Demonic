<?php

namespace vale\sage\demonic\Trojan\Task;

use pocketmine\scheduler\Task;
use vale\sage\demonic\staff\StaffManager;
use vale\sage\demonic\Trojan\TrojanAPI;
use vale\sage\demonic\Loader;

class MovingTask extends Task
{
    public function onRun(): void
    {
        $online = Loader::getInstance()->getServer()->getOnlinePlayers();
        foreach ($online as $player){
            $player_name = $player->getName();
            if(isset(Loader::$trojan["moving"]["cache"][$player_name])){
                if(isset(Loader::$trojan["moving"]["cache"][$player_name]["move"])){
                    if(Loader::$trojan["moving"]["cache"][$player_name]["move"] === null || Loader::$trojan["moving"]["cache"][$player_name]["move"] === 0){
                        TrojanAPI::setMoving($player_name, 0);
                    } else {
                        TrojanAPI::setMoving($player_name, Loader::$trojan["moving"]["cache"][$player_name]["move"]);
                        $ping = $player->getNetworkSession()->getPing();
                        $moving = TrojanAPI::getPlayerMoving($player_name);
                        if(isset(Loader::$trojan["moving"]["cache"][$player->getName()]["pos"]["speed"])){
                            $max_speed = Loader::$trojan["moving"]["cache"][$player->getName()]["pos"]["speed"];
                            $max_speed = (5 * $max_speed) + 20;
                        } else {
                            $max_speed = 20;
                        }
                        if($ping >= 150 && Loader::getInstance()->getServer()->getTicksPerSecond() >= 17 && $moving >= $max_speed){
                            $ping = $player->getNetworkSession()->getPing();
                            $moving = TrojanAPI::getPlayerMoving($player_name);
                            $staffManager = new StaffManager();
                            foreach(Loader::getInstance()->getServer()->getOnlinePlayers() as $staff) {
                                if ($staffManager->isInStaffMode($staff) || TrojanAPI::isAlert($staff)) {
                                    Loader::getInstance()->getServer()->broadcastMessage("§l§f<§4Trojan§f> §r§7$player_name May be hacker moving with $moving per second @ $ping MS");
                                }
                            }
                            TrojanAPI::addFlag($player_name, "moving",true);
                        } else {
                            if($moving >= $max_speed) {
                                TrojanAPI::addFlag($player_name, "moving", false);
                            }
                        }
                        TrojanAPI::update($player_name);
                    }
                }
            }
            TrojanAPI::resetMovingCache($player_name);
        }
    }
}