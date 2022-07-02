<?php

namespace vale\sage\demonic\Shop\GuiManager;

use pocketmine\player\Player;
use vale\sage\demonic\Shop\Category\CategoryBase;
use vale\sage\demonic\Shop\Category\CategoryBrewing;
use vale\sage\demonic\Shop\Category\CategoryBuilding;
use vale\sage\demonic\Shop\Category\CategoryClay;
use vale\sage\demonic\Shop\Category\CategoryConcrete;
use vale\sage\demonic\Shop\Category\CategoryFlower;
use vale\sage\demonic\Shop\Category\CategoryFood;
use vale\sage\demonic\Shop\Category\CategoryGlass;
use vale\sage\demonic\Shop\Category\CategoryOres;
use vale\sage\demonic\Shop\Category\CategoryPotion;
use vale\sage\demonic\Shop\Category\CategoryRaid;
use vale\sage\demonic\Shop\Category\CategorySpawners;
use vale\sage\demonic\Shop\Category\CategorySpecial;
use vale\sage\demonic\Shop\Category\CategoryWool;
use vale\sage\demonic\Shop\Category\CategoryMobs;
use vale\sage\demonic\Shop\Selector\ShopMain;
use vale\sage\demonic\Loader;

class GuiManager
{
    public static function OpenGui(Player $player,string $type){
        switch ($type){
            case "Main":
                Loader::getInstance()->getScheduler()->scheduleDelayedTask(new ShopMain($player),20);
                break;
            case "potion":
                Loader::getInstance()->getScheduler()->scheduleDelayedTask(new CategoryPotion($player),20);
                break;
            case "raid":
                Loader::getInstance()->getScheduler()->scheduleDelayedTask(new CategoryRaid($player),20);
                break;
            case "spawners":
                Loader::getInstance()->getScheduler()->scheduleDelayedTask(new CategorySpawners($player),20);
                break;
            case "buildings":
                Loader::getInstance()->getScheduler()->scheduleDelayedTask(new CategoryBuilding($player),20);
                break;
            case "ores":
                Loader::getInstance()->getScheduler()->scheduleDelayedTask(new CategoryOres($player),20);
                break;
            case "food":
                Loader::getInstance()->getScheduler()->scheduleDelayedTask(new CategoryFood($player),20);
                break;
            case "mob_drops":
                Loader::getInstance()->getScheduler()->scheduleDelayedTask(new CategoryMobs($player),20);
                break;
            case "speciality":
                Loader::getInstance()->getScheduler()->scheduleDelayedTask(new CategorySpecial($player),20);
                break;
            case "brewing":
                Loader::getInstance()->getScheduler()->scheduleDelayedTask(new CategoryBrewing($player),20);
                break;
            case "wool":
                Loader::getInstance()->getScheduler()->scheduleDelayedTask(new CategoryWool($player),20);
                break;
            case "glass":
                Loader::getInstance()->getScheduler()->scheduleDelayedTask(new CategoryGlass($player),20);
                break;
            case "base":
                Loader::getInstance()->getScheduler()->scheduleDelayedTask(new CategoryBase($player),20);
                break;
            case "clay":
                Loader::getInstance()->getScheduler()->scheduleDelayedTask(new CategoryClay($player),20);
                break;
            case "concrete":
                Loader::getInstance()->getScheduler()->scheduleDelayedTask(new CategoryConcrete($player),20);
                break;
            case "flower":
                Loader::getInstance()->getScheduler()->scheduleDelayedTask(new CategoryFlower($player),20);
                break;
        }
    }
}