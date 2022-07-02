<?php

namespace vale\sage\demonic\Partner\Item;

use vale\sage\demonic\Partner\cooldown\EnderPearl\EnderPearl;
use Partner\PartnerAPI;
use Partner\Task\TeleportPlayer;
use pocketmine\event\player\PlayerItemUseEvent;
use pocketmine\item\Item;
use pocketmine\item\ItemFactory;
use pocketmine\item\ItemIds;
use vale\sage\demonic\Loader;

class TimeWarp
{
    public static function onUse(PlayerItemUseEvent $event){
        $item = $event->getItem();
        $player = $event->getPlayer();
        if($item->getNamedTag()->getTag("Partner") !== null){
            if($item->getNamedTag()->getString("Partner") === "TimeWarp"){
                if (PartnerAPI::getIsInCooldown($player, "TimeWarp") === false) {
                    if (PartnerAPI::isPartnerCoolDown($player) === false) {
                        if(EnderPearl::haveCooldown($player)){
                            $position = EnderPearl::getLastHit($player);
                            Loader::getInstance()->getScheduler()->scheduleDelayedTask(new TeleportPlayer($player,$position,"§aSuccessfully teleport to last hit position"),1);
                            $player->sendMessage("§eYou have successfully used §dTime-Warp");
                            $player->sendMessage("§eNow cooldown for §d25 seconds");
                            $player->sendMessage("§eEnder Pearl cooldown has been reset!");
                            PartnerAPI::setCooldown($player, "TimeWarp");
                            PartnerAPI::setPartnerCooldown($player);
                            EnderPearl::removeCoolDown($player);
                        } else {
                            $player->sendMessage("§cYou didn't in Ender Pearl cooldown!");
                        }
                    } else {
                        $player->sendMessage("§cYou can't use Partner Item now, need to wait §d" . PartnerAPI::getPartnerCoolDown($player) . " seconds");
                    }
                } else {
                    $player->sendMessage("§cYou can't use §dTime-Warp §cfor §d" . PartnerAPI::getCoolDown($player,"TimeWarp") . " seconds");
                }
            }
        }
    }

    public static function getItem(): Item{
        $item = ItemFactory::getInstance()->get(ItemIds::FEATHER);
        $item->setCustomName("§r§l§3Time-Warp");
        $item->setLore(["§r§7While on pearl cooldown, right click\nto teleport back where you last\nthrew a pearl. Your pearl cooldown\nwill also be reset.\n\n> §3play.genesispvp.com§7 <"]);
        $item->getNamedTag()->setString("Partner","TimeWarp");
        return $item;
    }
}