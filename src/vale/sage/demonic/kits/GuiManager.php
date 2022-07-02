<?php

namespace vale\sage\demonic\kits;

use pocketmine\player\Player;
use vale\sage\demonic\kits\Category\CategoryTesting;
use vale\sage\demonic\Loader;

class GuiManager
{
    public static function openGui(Player $player,string $type){
        switch ($type){
            case "testing category":
                Loader::getInstance()->getScheduler()->scheduleDelayedTask(new CategoryTesting($player),20);
                break;
        }
    }

    public static function openPreview(Player $player,string $title,array $item){
        Loader::getInstance()->getScheduler()->scheduleDelayedTask(new previewGui($player,$item,$title),20);
    }
}