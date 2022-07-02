<?php

namespace vale\sage\demonic\Partner\Item;

use Partner\PartnerAPI;
use pocketmine\entity\effect\EffectInstance;
use pocketmine\entity\effect\VanillaEffects;
use pocketmine\event\player\PlayerItemUseEvent;
use pocketmine\item\Item;
use pocketmine\item\ItemFactory;
use pocketmine\item\ItemIds;
use pocketmine\player\Player;

class NotRamix
{
    public function onUse(PlayerItemUseEvent $event){
        $player = $event->getPlayer();
        $item = $event->getItem();
        if($item->getNamedTag()->getTag("Partner") !== null){
            if($item->getNamedTag()->getString("Partner") === "NotRamix"){
                if (PartnerAPI::getIsInCooldown($event->getPlayer(), "NotRamix") === false) {
                    if (PartnerAPI::isPartnerCoolDown($event->getPlayer()) === false) {
                        $count = 0;
                        $entity = $player->getWorld()->getNearbyEntities($player->getBoundingBox()->expandedCopy(10, 10, 10));
                        foreach ($entity as $e) {
                            if ($e instanceof Player && $e->getName() !== $player->getName()) {
                                $count++;
                            }
                        }
                        if ($count > 2) {
                            $player->sendMessage("§eYou have successfully used §dNotRamix's Rage Brick");
                            $player->sendMessage("§eNow cooldown for §d4 minutes");
                            $player->getEffects()->add(new EffectInstance(VanillaEffects::STRENGTH(), (20 * 12), 1));
                            $player->sendMessage("§aYou got 12 seconds of Strength II!");
                            $player->getInventory()->removeItem($item->setCount(1));
                            PartnerAPI::setCooldown($player, "NotRamix");
                            PartnerAPI::setPartnerCooldown($player);
                        } else {
                            if ($count === 0) {
                                $player->sendMessage("§cThere are no players around you!");
                            } else {
                                $player->getEffects()->add(new EffectInstance(VanillaEffects::STRENGTH(), (20 * 3 * $count), 1));
                                $player->sendMessage("§eYou have successfully used §dNotRamix's Rage Brick");
                                $player->sendMessage("§eNow cooldown for §d4 minutes");
                                $player->sendMessage("§aYou got " . $count * 3 . " seconds of Strength II!");
                                $player->getInventory()->removeItem($item->setCount(1));
                                PartnerAPI::setCooldown($player, "NotRamix");
                                PartnerAPI::setPartnerCooldown($player);
                            }
                        }
                    } else {
                        $player->sendMessage("§cYou can't use Partner Item now, need to wait §d" . PartnerAPI::getPartnerCoolDown($player) . " seconds");
                    }
                } else {
                    $player->sendMessage("§cYou can't use §dNotRamix's Rage Brick §cfor §d" . PartnerAPI::getCoolDown($player,"NotRamix") . " seconds");
                }
            }
        }
    }

    public static function getItem() : Item{
        $item = ItemFactory::getInstance()->get(ItemIds::NETHER_BRICK);
        $item->setCustomName("§r§l§3NotRamix's Rage Brick");
        $item->setLore(["§r§7Right click to receive 3 seconds\nof Strength II for every enemy\nwithin a 10 block radius of you.\nMaximum of 12 seconds.\n\n> §3play.genesispvp.com§7 <"]);
        $item->getNamedTag()->setString("Partner","NotRamix");
        return $item;
    }
}