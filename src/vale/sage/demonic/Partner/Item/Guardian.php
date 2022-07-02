<?php

namespace vale\sage\demonic\Partner\Item;

use Partner\PartnerAPI;
use pocketmine\event\player\PlayerItemUseEvent;
use pocketmine\item\Item;
use pocketmine\item\ItemFactory;
use pocketmine\item\ItemIds;

class Guardian
{
    public function onUse(PlayerItemUseEvent $event){
        $item = $event->getItem();
        $player = $event->getPlayer();
        if($item->getNamedTag()->getTag("Partner") !== null){
            if($item->getNamedTag()->getString("Partner") === "Guardian"){
                if (PartnerAPI::getIsInCooldown($event->getPlayer(), "Guardian") === false) {
                    if (PartnerAPI::isPartnerCoolDown($event->getPlayer()) === false) {
                        $health = $player->getHealth();
                        if ($health < 6) {
                            $player->setHealth(20);
                            $player->sendMessage("§eYou have successfully used §dGuardian Angel");
                            $player->sendMessage("§eNow cooldown for §d4 minutes");
                            $player->getInventory()->removeItem($item->setCount(1));
                            PartnerAPI::setCooldown($player, "Guardian");
                            PartnerAPI::setPartnerCooldown($player);
                        } else {
                            $player->sendMessage("§cYour health must below 3 to use it!");
                        }
                    } else {
                        $player->sendMessage("§cYou can't use Partner Item now, need to wait §d" . PartnerAPI::getPartnerCoolDown($player) . " seconds");
                    }
                } else {
                    $player->sendMessage("§cYou can't use §dGuardian Angel §cfor §d" . PartnerAPI::getCoolDown($player,"Guardian") . " seconds");
                }
            }
        }
    }

    public static function getItem(): Item{
        $item = ItemFactory::getInstance()->get(ItemIds::CLOCK);
        $item->setCustomName("§r§l§3Guardian Angel");
        $item->setLore(["§r§7Upon right clicking, if you go\nbelow 3 hearts, you will instantly\nheal to 10 hearts.\n\n> §3play.genesispvp.com§7 <"]);
        $item->getNamedTag()->setString("Partner", "Guardian");
        return $item;
    }
}