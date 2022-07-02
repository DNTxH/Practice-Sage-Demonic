<?php

namespace vale\sage\demonic\Trojan;

use pocketmine\block\BlockLegacyIds;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerMoveEvent;
use vale\sage\demonic\staff\StaffManager;
use vale\sage\demonic\Loader;

class Phase implements Listener
{
    public function onMove(PlayerMoveEvent $event){
        $block = $event->getPlayer()->getWorld()->getBlock($event->getPlayer()->getPosition()->add(0, 0, 0));
        $player = $event->getPlayer();
        if($block instanceof Opaque or $block instanceof Transparent){
            if($block instanceof Liquid){
                return true;
            }
            if($block instanceof Door || $block instanceof FenceGate){
                return true;
            }
            if($block->canBeFlowedInto()){
                return true;
            }
            $ping = $event->getPlayer()->getNetworkSession()->getPing();
            $tps = Loader::getInstance()->getServer()->getTicksPerSecond();
            $staffManager = new StaffManager();
            if($ping >= 150 && $tps >= 17){
                TrojanAPI::addFlag($event->getPlayer(), "phase",true);
            } else {
                TrojanAPI::addFlag($event->getPlayer(), "phase");
            }
            foreach(Loader::getInstance()->getServer()->getOnlinePlayers() as $staff){
                if($staffManager->isInStaffMode($staff) || TrojanAPI::isAlert($staff)) {
                    $staff->sendMessage("§r§f§l<§r§4Trojan§f§l> §r§7The player " . $player->getName() . " could be phasing. " . " §r§c§lPing §r§7{$player->getNetworkSession()->getPing()} §r§7MS");
                }
            }
            $from = $event->getFrom();
            $to = $event->getTo();
            $x = $from->x - $to->x;
            $y = $from->y - $to->y;
            $z = $from->z - $to->z;
            $pos = $event->getFrom()->add($x,$y,$z);
            $event->getPlayer()->teleport($pos, $event->getPlayer()->getLocation()->getYaw(), $event->getPlayer()->getLocation()->getPitch());
        }
        return true;
    }
}