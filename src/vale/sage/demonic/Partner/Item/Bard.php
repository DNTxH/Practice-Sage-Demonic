<?php

namespace vale\sage\demonic\Partner\Item;

use Partner\PartnerAPI;
use pocketmine\event\player\PlayerInteractEvent;
use pocketmine\event\player\PlayerItemUseEvent;
use pocketmine\item\Item;
use pocketmine\item\ItemFactory;
use pocketmine\item\ItemIds;
use pocketmine\player\Player;
use vale\sage\demonic\Loader;

class Bard
{
    public static function getItem(): Item{
        $dye = ItemFactory::getInstance()->get(ItemIds::DYE, 4);
        $dye->setCustomName("§r§l§3Portable Bard");
        $dye->setLore(["§r§7Give you team a bard effect\nwithout bard armor equipped\n\n> §3play.genesispvp.com§7 <"]);
        $dye->getNamedTag()->setString("Partner", "Bard");
        return $dye;
    }

    public static function onUse(PlayerItemUseEvent $event){
        $item = $event->getItem();
        if($item->getNamedTag()->getTag("Partner") !== null) {
            if ($item->getNamedTag()->getString("Partner") === "Bard") {
                if (PartnerAPI::getIsInCooldown($event->getPlayer(), "Bard") === false) {
                    if (PartnerAPI::isPartnerCoolDown($event->getPlayer()) === false) {
                        $event->getPlayer()->getInventory()->remove($item->setCount(1));
                        $bard = new \Partner\Entity\Bard($event->getPlayer()->getLocation());
                        $bard->setOwner($event->getPlayer());
                        $bard->setPos($event->getPlayer()->getLocation());
                        $bard->spawnToAll();
                        $event->getPlayer()->sendMessage("§eYou have successfully used §dBard");
                        $event->getPlayer()->sendMessage("§eNow cooldown for §d4 minutes");
                        Loader::$bard_allow[$event->getPlayer()->getName()] = true;
                        PartnerAPI::setCooldown($event->getPlayer(), "Bard");
                        PartnerAPI::setPartnerCooldown($event->getPlayer());
                    } else {
                        $event->getPlayer()->sendMessage("§cYou can't use Partner Item now, need to wait §d" . PartnerAPI::getPartnerCoolDown($event->getPlayer()) . " seconds");
                        $event->cancel();
                    }
                } else {
                    $event->getPlayer()->sendMessage("§cYou can't use §dBard §cfor §d" . PartnerAPI::getCoolDown($event->getPlayer(),"Bard") . " seconds");
                    $event->cancel();
                }
            }
        }
    }

    public static function isAllow(Player $player){
        if(!isset(Loader::$bard_allow[$player->getName()])){
            return false;
        }
        return true;
    }

    public static function setAllow(Player $player){
        Loader::$bard_allow[$player->getName()] = true;
    }

    public static function removeAllow(Player $player){
        unset(Loader::$bard_allow[$player->getName()]);
    }
}