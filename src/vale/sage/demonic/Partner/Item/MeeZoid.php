<?php

namespace vale\sage\demonic\Partner\Item;

use Partner\PartnerAPI;
use pocketmine\entity\effect\EffectInstance;
use pocketmine\entity\effect\VanillaEffects;
use pocketmine\event\block\BlockBreakEvent;
use pocketmine\event\block\BlockPlaceEvent;
use pocketmine\event\entity\EntityDamageByEntityEvent;
use pocketmine\item\Item;
use pocketmine\item\ItemFactory;
use pocketmine\item\ItemIds;
use pocketmine\player\Player;
use vale\sage\demonic\Loader;

class MeeZoid
{


    public function onDamage(EntityDamageByEntityEvent $event){
        $player = $event->getEntity();
        $damager = $event->getDamager();
        if($player instanceof Player && $damager instanceof Player){
            $item = $damager->getInventory()->getItemInHand();
            if($item->getNamedTag()->getTag("Partner") !== null){
                if($item->getNamedTag()->getString("Partner") === "MeeZoid"){
                    if (PartnerAPI::getIsInCooldown($player, "MeeZoid") === false) {
                        if (PartnerAPI::isPartnerCoolDown($player) === false) {
                            if (isset(Loader::$meezoid["combo"][$damager->getName()])) {
                                if (isset(Loader::$meezoid["combo"][$damager->getName()]["enemy"])) {
                                    $combo = Loader::$meezoid["combo"][$damager->getName()]["enemy"];
                                    if ($combo === $player->getName()) {
                                        $combo = Loader::$meezoid["combo"][$damager->getName()]["combo"];
                                        if ($combo < 2) {
                                            Loader::$meezoid["combo"][$damager->getName()]["combo"] = $combo + 1;
                                            $damager->sendMessage("§bStill need more " . (2 - $combo) . " hit to active MeeZoid's!");
                                        } else {
                                            $damager->sendMessage("§eYou have successfully used §dMeeZoid's Exotic Bone");
                                            $damager->sendMessage($player->getName() . " can't place and break blocks for 15 seconds!");
                                            $damager->sendMessage("§eNow cooldown for §d4 minutes");
                                            $damager->getInventory()->removeItem($item->setCount(1));
                                            $damager->getEffects()->add(new EffectInstance(VanillaEffects::STRENGTH(), 20 * 10, 1));
                                            $damager->getEffects()->add(new EffectInstance(VanillaEffects::SPEED(), 20 * 10, 2));
                                            $player->sendMessage("§cYou have been activated by MeeZoid!");
                                            $player->sendMessage("§cYou have been limited to place and break block for 15 second!");
                                            PartnerAPI::setCooldown($player, "MeeZoid");
                                            PartnerAPI::setPartnerCooldown($player);
                                            unset(Loader::$meezoid["combo"][$damager->getName()]);
                                            Loader::$meezoid["limit"][$player->getName()] = time();
                                        }
                                    } else {
                                        Loader::$meezoid["combo"][$damager->getName()]["combo"] = 1;
                                        Loader::$meezoid["combo"][$damager->getName()]["enemy"] = $player->getName();
                                        $damager->sendMessage("§bStill need more " . (2) . " hit to active MeeZoid's!");
                                    }
                                } else {
                                    Loader::$meezoid["combo"][$damager->getName()]["combo"] = 1;
                                    $damager->sendMessage("§bStill need more  " . (2) . " hit to active MeeZoid's!");
                                }
                            } else {
                                Loader::$meezoid["combo"][$damager->getName()]["combo"] = 1;
                                Loader::$meezoid["combo"][$damager->getName()]["enemy"] = $player->getName();
                                $damager->sendMessage("§bStill need more " . (2) . " hit to active MeeZoid's!");
                            }
                        } else {
                            $player->sendMessage("§cYou can't use Partner Item now, need to wait §d" . PartnerAPI::getPartnerCoolDown($player) . " seconds");
                        }
                    } else {
                        $player->sendMessage("§cYou can't use §dMeeZoid's Exotic Bone §cfor §d" . PartnerAPI::getCoolDown($player,"MeeZoid") . " seconds");
                    }
                }
            }
        }
    }

    public function onPlace(BlockPlaceEvent $event){
        $player = $event->getPlayer();
        if(isset(Loader::$meezoid["limit"][$player->getName()])){
            if(time() - Loader::$meezoid["limit"][$player->getName()] < 15){
                $event->cancel();
                $player->sendMessage("§cYou can't place block for ". (Loader::$meezoid["limit"][$player->getName()] + 15) - time()  ." second!");
            } else {
                unset(Loader::$meezoid["limit"][$player->getName()]);
            }
        }
    }

    public function onBreak(BlockBreakEvent $event){
        $player = $event->getPlayer();
        if(isset(Loader::$meezoid["limit"][$player->getName()])){
            if(time() - Loader::$meezoid["limit"][$player->getName()] < 15){
                $event->cancel();
                $player->sendMessage("§cYou can't place block for ". (Loader::$meezoid["limit"][$player->getName()] + 15) - time()  ." second!");
            } else {
                unset(Loader::$meezoid["limit"][$player->getName()]);
            }
        }
    }

    public static function getItem(): Item{
        $item = ItemFactory::getInstance()->get(ItemIds::BONE);
        $item->setCustomName("§r§l§3MeeZoid's Exotic Bone");
        $item->setLore(["§r§7Hit a player 3 times and they won't\nbe able to place or break any blocks for\n15 seconds. You will also receive\nStrength II and Speed III for 10 seconds.\n\n> §3play.genesispvp.com§7 <"]);
        $item->getNamedTag()->setString("Partner", "MeeZoid");
        return $item;
    }
}