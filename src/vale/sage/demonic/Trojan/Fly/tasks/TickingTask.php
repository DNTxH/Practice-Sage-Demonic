<?php

namespace  vale\sage\demonic\Trojan\Fly\tasks;

use  vale\sage\demonic\Trojan\Fly\data\PlayerData;
use  vale\sage\demonic\Trojan\Fly\Esoteric;
use pocketmine\scheduler\Task;
use pocketmine\Server;
use function array_filter;

class TickingTask extends Task{

	public function onRun() : void{
		if(Server::getInstance()->getTick() % 40 === 0){
			Esoteric::getInstance()->hasAlerts = array_filter(Esoteric::getInstance()->dataManager->getAll(), static function(PlayerData $data) : bool{
				return $data->player !== null && !$data->player->isClosed() && $data->hasAlerts && $data->player->hasPermission("ac.alerts");
			});
		}
		foreach(Esoteric::getInstance()->dataManager->getAll() as $playerData){
			$playerData->tickProcessor->execute($playerData);
		}
	}

}