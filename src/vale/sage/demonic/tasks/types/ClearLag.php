<?php

namespace vale\sage\demonic\tasks\types;

use vale\sage\demonic\Loader;
use vale\sage\demonic\utils\Utils;
use pocketmine\player\Player;
use pocketmine\scheduler\Task;

class ClearLag extends Task
{

    private int $original;
    public static int $seconds;
    private array $times = [
        1800,
        1200,
        900,
        600,
        300,
        60,
        30,
        15,
        10,
        5,
        4,
        3,
        2,
        1
    ];

    public function __construct(int $seconds){
        $this->original = $seconds;
        self::$seconds = $seconds;
    }

    public function onRun(): void{
        if(in_array(self::$seconds, $this->times)){
            Loader::getInstance()->getServer()->broadcastMessage("§aAll entities will clear in " . Utils::translateTime(self::$seconds));
        }
        self::$seconds--;
        if(self::$seconds == 0){
            self::$seconds = $this->original;
            self::clearEntities();
        }
    }

    public static function clearEntities(){
        foreach(Loader::getInstance()->getServer()->getWorldManager()->getWorlds() as $level){
            foreach($level->getEntities() as $entity){
                if(!$entity instanceof Player){
                    $entity->flagForDespawn();
                }
            }
        }
        Loader::getInstance()->getServer()->broadcastMessage("§aAll entities have been cleared");
    }

}