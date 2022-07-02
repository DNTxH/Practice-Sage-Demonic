<?php

namespace vale\sage\demonic\Partner\Item;

use Partner\PartnerAPI;
use Partner\Task\TeleportPlayer;
use pocketmine\event\entity\ProjectileHitEntityEvent;
use pocketmine\event\player\PlayerItemUseEvent;
use pocketmine\item\Item;
use pocketmine\item\ItemFactory;
use pocketmine\item\ItemIds;
use pocketmine\player\Player;
use vale\sage\demonic\Loader;

class AntiTrap
{
    public function onUse(PlayerItemUseEvent $event){
        $item = $event->getItem();
        $player = $event->getPlayer();
        if($item->getNamedTag()->getTag("Partner") !== null){
            if($item->getNamedTag()->getString("Partner") === "AntiTrap"){
                if(isset(Loader::$antiTrap[$player->getName()])) {
                    $attacker = Loader::$antiTrap[$player->getName()];
                    $attacker = Loader::getInstance()->getServer()->getPlayerExact($attacker);
                    if($attacker){
                        if(PartnerAPI::getIsInCooldown($player,"AntiTrap") === false) {
                            if(PartnerAPI::isPartnerCoolDown($player) === false) {
                                $player->sendMessage("§eYou have successfully used §dAnti-Trap Star");
                                $player->sendMessage("§eNow cooldown for §d4 minutes");
                                $player->sendMessage("§aYou'll be teleport to " . $attacker->getName() . " after 5 seconds");
                                Loader::getInstance()->getScheduler()->scheduleDelayedTask(new TeleportPlayer($player, $attacker->getPosition(), "§aSuccessfully teleport to " . $attacker->getName()), 20 * 5);
                                $player->getInventory()->removeItem($item->setCount(1));
                                PartnerAPI::setCooldown($player, "AntiTrap");
                                PartnerAPI::setPartnerCooldown($player);
                            } else {
                                $player->sendMessage("§cYou can't use Partner Item now, need to wait §d" . PartnerAPI::getPartnerCoolDown($player) . " seconds");
                            }
                        } else {
                            $player->sendMessage("§cYou can't use §dAnti-Trap Star §cfor §d" . PartnerAPI::getCoolDown($player,"AntiTrap") . " seconds");
                        }
                    } else {
                        $player->sendMessage("§cThe player who attack you was offline!");
                    }
                } else {
                    $player->sendMessage("§cNo one attack you!");
                }
            }
        }
    }

    public function damage(ProjectileHitEntityEvent $event){
        $attacker = $event->getEntity()->getOwningEntity();
        $damager = $event->getEntityHit();
        if($attacker instanceof Player && $damager instanceof Player){
            Loader::$antiTrap[$damager->getname()] = $attacker->getName();
        }
    }

    public static function getItem(): Item{
        $item = ItemFactory::getInstance()->get(ItemIds::NETHER_STAR);
        $item->setCustomName("§r§l§3Anti-Trap Star");
        $item->setLore(["§r§7After being hit with a projectile,\nright click to teleport to the player\nwho hit you with the projectile.\n\n> §3play.genesispvp.com§7 <"]);
        $item->getNamedTag()->setString("Partner", "AntiTrap");
        return $item;
    }
}