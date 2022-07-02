<?php

namespace vale\sage\demonic\Partner\Item;

use Partner\PartnerAPI;
use Partner\Task\TeleportPlayer;
use pocketmine\event\entity\EntityDamageByEntityEvent;
use pocketmine\event\player\PlayerInteractEvent;
use pocketmine\event\player\PlayerItemUseEvent;
use pocketmine\item\Item;
use pocketmine\item\ItemFactory;
use pocketmine\item\ItemIds;
use pocketmine\player\Player;
use vale\sage\demonic\Loader;

class Ninja
{
    public function onDamage(EntityDamageByEntityEvent $event){
        $player = $event->getEntity();
        $damager = $event->getDamager();
        if($player instanceof Player && $damager instanceof Player){
            Loader::$ninja_hit[$player->getName()] = array("damager" => $damager->getName(),"time" => time());
        }
    }

    public function onUse(PlayerItemUseEvent $event){
        $player = $event->getPlayer();
        $item = $event->getItem();
        if($item->getNamedTag()->getTag("Partner") !== null) {
            if($item->getNamedTag()->getString("Partner") === "ninja") {
                if (PartnerAPI::getIsInCooldown($event->getPlayer(), "ninja") === false) {
                    if (PartnerAPI::isPartnerCoolDown($event->getPlayer()) === false) {
                        if (isset(Loader::$ninja_hit[$player->getName()])) {
                            $damager = Loader::$ninja_hit[$player->getName()]["damager"];
                            $time = Loader::$ninja_hit[$player->getName()]["time"];
                            if (time() - $time < 10) {
                                $damager = Loader::getInstance()->getServer()->getPlayerExact($damager);
                                if ($damager) {
                                    Loader::getInstance()->getScheduler()->scheduleDelayedTask(new TeleportPlayer($player,$damager->getPosition(),"§aSuccessfully teleport to " . $damager->getName()),20 * 5);
                                    $player->sendMessage("§eYou have successfully used §dNinja Ability");
                                    $player->sendMessage("§eNow cooldown for §d4 minutes");
                                    $player->sendMessage("§aYou'll be teleport to " . $damager->getName() . " after 5 seconds");
                                    $player->getInventory()->remove($player->getInventory()->getItemInHand()->setCount(1));
                                    PartnerAPI::setCooldown($player, "ninja");
                                    PartnerAPI::setPartnerCooldown($player);
                                } else {
                                    $player->sendMessage("§cThe player that hit you within 10 second is offline!");
                                }
                            } else {
                                $player->sendMessage("§cNo one hit you within 10 second!");
                            }
                        } else {
                            $player->sendMessage("§cNo one hit you within 10 second!");
                        }
                    } else {
                        $player->sendMessage("§cYou can't use Partner Item now, need to wait §d" . PartnerAPI::getPartnerCoolDown($player) . " seconds");
                    }
                } else {
                    $player->sendMessage("§cYou can't use §dNinja Ability §cfor §d" . PartnerAPI::getCoolDown($player,"ninja") . " seconds");
                }
            }
        }
    }

    public static function getItem(): Item{
        $item = ItemFactory::getInstance()->get(ItemIds::NETHER_STAR);
        $item->setCustomName("§r§l§3Ninja Ability");
        $item->setLore(["§r§7Right click to teleport to the last\nplayer who hit you within 10 second!\n\n> §3play.genesispvp.com§7 <"]);
        $item->getNamedTag()->setString("Partner", "ninja");
        return $item;
    }
}