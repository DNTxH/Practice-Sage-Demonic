<?php

namespace vale\sage\demonic\Partner\cooldown\EnderPearl;

use pocketmine\event\entity\ProjectileHitBlockEvent;
use pocketmine\event\player\PlayerItemUseEvent;
use pocketmine\player\Player;
use pocketmine\utils\TextFormat;
use vale\sage\demonic\enchants\enchantments\type\soul\Teleblock;
use vale\sage\demonic\Loader;

class EnderPearl
{
    public static function onUse(PlayerItemUseEvent $event){
        $item = $event->getItem();
        $player = $event->getPlayer();
        if($item instanceof \pocketmine\item\EnderPearl){
            if(in_array($player->getUniqueId()->toString(), Teleblock::$teleblocked)) {
                $player->sendMessage(TextFormat::RED . "You are currently teleblocked!");
                $event->cancel();
                return;
            }

            if(self::haveCooldown($player)){
                $player->sendMessage("§cYou can't use Ender Pearl right now, wait ".self::getCooldown($player)." seconds");
                $event->cancel();
            } else {
                self::setCooldown($player);
                Loader::$enderPearl["lastUse"][$player->getName()] = $player->getPosition();
            }
        }
    }


    public static function getLastHit(Player $player){
        if(isset(Loader::$enderPearl["lastUse"][$player->getName()])){
            return Loader::$enderPearl["lastUse"][$player->getName()];
        }
        return false;
    }

    public static function haveCooldown(Player $player){
        if(isset(Loader::$enderPearl["coolDown"][$player->getName()])){
            $time = Loader::$enderPearl["coolDown"][$player->getName()];
            if(time() - $time >= 16){
                return false;
            } else {
                return true;
            }
        }
        return false;
    }

    public static function setCoolDown(Player $player){
        Loader::$enderPearl["coolDown"][$player->getName()] = time();
    }

    public static function getCooldown(Player $player){
        $time = Loader::$enderPearl["coolDown"][$player->getName()];
        return 16 - (time() - $time);
    }

    public static function removeCoolDown(Player $player){
        unset(Loader::$enderPearl["coolDown"][$player->getName()]);
    }
}