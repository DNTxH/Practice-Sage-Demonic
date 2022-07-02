<?php
namespace vale\sage\demonic\tasks\types;

use vale\sage\demonic\Loader;
use vale\sage\demonic\utils\Utils;
use pocketmine\scheduler\Task;
use pocketmine\Server;

class MotdTask extends Task{


    	public function onRun(): void{
        /** @var array $input */
        $input = [
        " §r§dGenesis§8PVP",
        "§l§5Gen§desis§8P§7V§8P",
		"§l§5Genesis§7PVP"
        ];
        $details = array_rand($input);
		Server::getInstance()->getNetwork()->setName($input[$details]);
    }
}