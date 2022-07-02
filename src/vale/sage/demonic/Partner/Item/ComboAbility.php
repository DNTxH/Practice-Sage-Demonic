<?php

namespace vale\sage\demonic\Partner\Item;

use Partner\PartnerAPI;
use Partner\Task\ComboAbility\EndTask;
use pocketmine\entity\effect\EffectInstance;
use pocketmine\entity\effect\VanillaEffects;
use pocketmine\event\entity\EntityDamageByEntityEvent;
use pocketmine\event\player\PlayerItemUseEvent;
use pocketmine\item\Item;
use pocketmine\item\ItemFactory;
use pocketmine\item\ItemIds;
use pocketmine\player\Player;
use vale\sage\demonic\Loader;

class ComboAbility
{
    public function onUse(PlayerItemUseEvent $event){
        $item = $event->getItem();
        $player = $event->getPlayer();
        if($item->getNamedTag()->getTag("Partner") !== null){
            if($item->getNamedTag()->getString("Partner") === "ComboAbility"){
                $combo = Loader::$comboAbility;
                if(isset($combo["use"][$player->getName()])){
                    if (PartnerAPI::getIsInCooldown($event->getPlayer(), "Combo") === false) {
                        if (PartnerAPI::isPartnerCoolDown($event->getPlayer()) === false) {
                            if (time() - $combo["use"][$player->getName()]["time"] > 10) {
                                $combo["use"][$player->getName()]["time"] = time();
                                $combo["hit"][$player->getName()] = 0;
                                $player->sendMessage("§eYou have successfully used §Combo Ability");
                                $player->sendMessage("§eNow cooldown for §d4 minutes");
                                Loader::getInstance()->getScheduler()->scheduleDelayedTask(new EndTask($player), 20 * 12);
                                $player->getInventory()->removeItem($item->setCount(1));
                                PartnerAPI::setCooldown($player, "Combo");
                                PartnerAPI::setPartnerCooldown($player);
                                if (!isset($combo["hit"][$player->getName()])) {
                                    $combo["hit"][$player->getName()] = 0;
                                }
                            } else {
                                $player->sendMessage("§cYou are already using a Combo Ability, please wait " . ($combo["use"][$player->getName()]["time"] + 10) - time() . " seconds");
                            }
                        } else {
                            $player->sendMessage("§cYou can't use Partner Item now, need to wait §d" . PartnerAPI::getPartnerCoolDown($player) . " seconds");
                        }
                    } else {
                        $player->sendMessage("§cYou can't use §dCombo Ability §cfor §d" . PartnerAPI::getCoolDown($player,"Combo") . " seconds");
                    }
                } else {
                    $combo["use"][$player->getName()] = array("time" => time());
                    if(!isset($combo["hit"][$player->getName()])){
                        $combo["hit"][$player->getName()] = 0;
                    }
                    Loader::getInstance()->getScheduler()->scheduleDelayedTask(new EndTask($player), 20 * 12);
                    $player->sendMessage("§aCombo Ability was activated!");
                    $player->getInventory()->removeItem($item->setCount(1));
                }
                Loader::$comboAbility = $combo;
            }
        }
    }

    public function onDamage(EntityDamageByEntityEvent $event){
        $player = $event->getEntity();
        $damager = $event->getDamager();
        if($player instanceof Player && $damager instanceof Player){
            $combo = Loader::$comboAbility;
            if(isset($combo["use"][$damager->getName()])){
                $time = $combo["use"][$damager->getName()]["time"];
                if(time() - $time < 10){
                    $combo["hit"][$damager->getName()] = $combo["hit"][$damager->getName()] + 1;
                } else {
                    unset($combo["use"][$damager->getName()]);
                }
            }
            Loader::$comboAbility = $combo;
        }
    }

    public static function getItem():Item{
        $item = ItemFactory::getInstance()->get(ItemIds::PUFFERFISH);
        $item->setCustomName("§r§l§3Combo Ability");
        $item->setLore(["§r§7Right click to begin a 10 second period where\neach hit to an enemy gives a second of\nStrength II. Capped at 12 Seconds.\n\n> §3play.genesispvp.com§7 <"]);
        $item->getNamedTag()->setString("Partner", "ComboAbility");
        return $item;
    }
}