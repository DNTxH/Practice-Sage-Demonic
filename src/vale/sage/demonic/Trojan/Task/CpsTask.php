<?php

namespace vale\sage\demonic\Trojan\Task;

use pocketmine\scheduler\Task;
use vale\sage\demonic\staff\StaffManager;
use vale\sage\demonic\Trojan\TrojanAPI;
use vale\sage\demonic\Loader;

class CpsTask extends Task
{
    public function onRun(): void
    {
        foreach(Loader::$trojan["cps"]["cache"] as $player => $cps){

            TrojanAPI::setCps($player, $cps);
            if($cps >= 25){
                $ping = Loader::getInstance()->getServer()->getPlayerExact($player)->getNetworkSession()->getPing();
                $staffManager = new StaffManager();
                foreach(Loader::getInstance()->getServer()->getOnlinePlayers() as $staff) {
                    if ($staffManager->isInStaffMode($staff) || TrojanAPI::isAlert($staff)) {
                        Loader::getInstance()->getServer()->broadcastMessage("§l§f<§4Trojan§f> §r§7$player May be AutoClicking with $cps CPS @ $ping MS");
                    }
                }
                if($ping > 150) {
                    TrojanAPI::addFlag($player, "cps",true);
                } else {
                    TrojanAPI::addFlag($player, "cps",false);
                }

            }
            TrojanAPI::update($player);
            TrojanAPI::resetCacheCps($player);
        }
    }
}