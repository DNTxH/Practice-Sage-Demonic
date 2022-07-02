<?php

namespace vale\sage\demonic\Partner\Item;

use Partner\PartnerAPI;
use pocketmine\event\player\PlayerItemUseEvent;
use pocketmine\item\Item;
use pocketmine\item\ItemFactory;
use pocketmine\item\ItemIds;
use vale\sage\demonic\Loader;

class HateFoo
{

    public function onUse(PlayerItemUseEvent $event){
        $player = $event->getPlayer();
        $item = $event->getItem();
        if($item->getNamedTag()->getTag("Partner") !== null){
            if($item->getNamedTag()->getString("Partner") === "HatFoo"){
                if (PartnerAPI::getIsInCooldown($event->getPlayer(), "HatFoo") === false) {
                    if (PartnerAPI::isPartnerCoolDown($event->getPlayer()) === false) {
                        if (isset(Loader::$ninja_hit[$player->getName()])) {
                            $attacker = Loader::$ninja_hit[$player->getName()];
                            if (time() - $attacker["time"] < 10) {
                                $attacker = Loader::getInstance()->getServer()->getPlayerExact($attacker["damager"]);
                                if ($attacker) {
                                    $effect = $attacker->getEffects()->all();
                                    if (count($effect) <= 0) {
                                        $player->sendMessage("§c Player " . $attacker->getName() . " didn't have any effect.");
                                        return true;
                                    }
                                    foreach ($effect as $e) {
                                        $player->getEffects()->add($e);
                                    }
                                    $attacker->getEffects()->clear();
                                    $player->sendMessage("§eYou have successfully used §dHateFoo's Effect Stealer");
                                    $player->sendMessage("§eNow cooldown for §d4 minutes");
                                    $attacker->sendMessage("§aYour Effect has been stolen by " . $player->getName() . " using §dHateFoo's Effect Stealer");
                                    $player->sendMessage("§aYou have stolen " . $attacker->getName() . "'s Effect!");
                                    $player->getInventory()->removeItem(self::getItem());
                                    Bard::removeAllow($attacker);
                                    PartnerAPI::setCooldown($player, "HatFoo");
                                    PartnerAPI::setPartnerCooldown($player);
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
                    $player->sendMessage("§cYou can't use §dHateFoo's Effect Stealer §cfor §d" . PartnerAPI::getCoolDown($player,"HatFoo") . " seconds");
                }
            }
        }
    }

    public static function getItem():Item{
        $item = ItemFactory::getInstance()->get(ItemIds::SLIME_BALL);
        $item->getNamedTag()->setString("Partner", "HatFoo");
        $item->setCustomName("§r§l§3HateFoo's Effect Stealer");
        $item->setLore(["§r§7Steal the potion effects of the last\nplayer who hit you for 10 seconds.\nThe player will lose all effects\nand cannot receive Bard effects.\n\n> §3play.genesispvp.com§7 <"]);
        return $item;
    }
}