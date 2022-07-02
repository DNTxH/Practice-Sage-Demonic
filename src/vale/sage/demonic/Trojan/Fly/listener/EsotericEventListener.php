<?php

namespace  vale\sage\demonic\Trojan\Fly\listener;

use  vale\sage\demonic\Trojan\Fly\Esoteric;
use pocketmine\event\Listener;
use pocketmine\event\server\DataPacketSendEvent;
use pocketmine\network\mcpe\protocol\StartGamePacket;
use pocketmine\network\mcpe\protocol\types\PlayerMovementSettings;
use pocketmine\network\mcpe\protocol\types\PlayerMovementType;

class EsotericEventListener implements Listener{

	public function send(DataPacketSendEvent $event) : void{
		foreach($event->getTargets() as $target){
			$playerData = Esoteric::getInstance()->dataManager->get($target);
			if($playerData === null){
				continue;
			}
			foreach($event->getPackets() as $packet){
				if($packet instanceof StartGamePacket){
					$packet->playerMovementSettings = new PlayerMovementSettings(PlayerMovementType::SERVER_AUTHORITATIVE_V2_REWIND, 20, false);
				}
				$playerData->outboundProcessor->execute($packet, $playerData);
			}
		}
	}
}
