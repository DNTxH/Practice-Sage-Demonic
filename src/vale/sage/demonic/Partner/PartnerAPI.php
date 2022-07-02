<?php

namespace vale\sage\demonic\Partner;

use vale\sage\demonic\Partner\Item\AntiTrap;
use vale\sage\demonic\Partner\Item\Bard;
use vale\sage\demonic\Partner\Item\ComboAbility;
use vale\sage\demonic\Partner\Item\Guardian;
use vale\sage\demonic\Partner\Item\HateFoo;
use vale\sage\demonic\Partner\Item\MeeZoid;
use vale\sage\demonic\Partner\Item\Ninja;
use vale\sage\demonic\Partner\Item\NotRamix;
use vale\sage\demonic\Partner\Item\TimeWarp;
use pocketmine\item\ItemFactory;
use pocketmine\item\ItemIds;
use pocketmine\player\Player;
use vale\sage\demonic\Loader;

class PartnerAPI
{
    public static function getItem(string $type){
        return match ($type) {
            "Ninja" => Ninja::getItem(),
            "Bard" => Bard::getItem(),
            "SnowBall" => ItemFactory::getInstance()->get(ItemIds::SNOWBALL,1,1),
            "HateFoo" => HateFoo::getItem(),
            "Guardian" => Guardian::getItem(),
            "MeeZoid" => MeeZoid::getItem(),
            "ComboAbility" => ComboAbility::getItem(),
            "NotRamix" => NotRamix::getItem(),
            "AntiTrap" => AntiTrap::getItem(),
            "TimeWarp" => TimeWarp::getItem(),
            default => false,
        };
    }

    public static function getIsInCooldown(Player $player,string $type){
        $list = Loader::$cooldown;
        if(isset($list[$type])) {
            if (isset($list[$type][$player->getName()])) {
                $time = $list[$type][$player->getName()];
                if (time() - $time > (60 * 4)) {
                    unset(Loader::$cooldown[$type][$player->getName()]);
                    return false;
                } else {
                    return true;
                }
            } else {
                return false;
            }
        }
        return false;
    }

    public static function setCooldown(Player $player,string $type){
        Loader::$cooldown[$type][$player->getName()] = time();
    }

    public static function getCoolDown(Player $player,string $type){
        if(isset(Loader::$cooldown[$type][$player->getName()])){
            $time = Loader::$cooldown[$type][$player->getName()];
            $minute = (int)((($time + (60 * 4)) - time()) / 60);
            $sec = (($time + (60 * 4)) - time()) % 60;
            return "§c{$minute}minute {$sec}second";
        }
        return false;
    }

    public static function isPartnerCoolDown(Player $player){
        $list = Loader::$cooldown;
        if(isset($list[$player->getName()])){
            $time = $list[$player->getName()];
            if(time() - $time > 20){
                unset(Loader::$cooldown[$player->getName()]);
                return false;
            } else {
                return true;
            }
        }
        return false;
    }

    public static function setPartnerCooldown(Player $player){
        Loader::$cooldown[$player->getName()] = time();
    }

    public static function getPartnerCoolDown(Player $player): bool|string
    {
        if(isset(Loader::$cooldown[$player->getName()])){
            $time = Loader::$cooldown[$player->getName()];
            return ($time + 20) - time();
        }
        return false;
    }
}