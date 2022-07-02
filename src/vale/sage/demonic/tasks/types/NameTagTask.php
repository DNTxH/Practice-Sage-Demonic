<?php
namespace vale\sage\demonic\tasks\types;

use pocketmine\scheduler\Task;
use pocketmine\Server;
use vale\sage\demonic\addons\types\brag\Brag;
use vale\sage\demonic\Loader;
use vale\sage\demonic\ranks\rank\RankIDS;

class NameTagTask extends Task
{

	/**
	 * Updates NameTag
	 */
	public function onRun(): void
	{
		foreach (Server::getInstance()->getOnlinePlayers() as $player) {
			if(Brag::isBragging($player)){
				$session = Brag::setBragging($player);
				$session->update();
				return;
			}
			$session = Loader::getInstance()->getSessionManager()->getSession($player);
			if ($session !== null && $session->getRank() !== null) {
				$rank = $session->getRank();
				$faction = $session->getFaction() !== null ? $session->getFaction()->getName() : "";
				$health = round($player->getHealth());
				$name = $player->getName();
				$session->sendDefaultScoreboard();
				$session->checkSets();
				Loader::getInstance()->getRankManager()->updateNametag($session);
                
                /** remove if work
				if($rank == 8) {
					$player->setNameTag("§r§f*$faction §r§f§l<§r§7Trainee§r§f§l> \n §r§7$name");
					$player->setScoreTag("§r§f$health §r§4§lHP");
				} elseif($rank > 8) {
					$player->setNameTag("§r§f*$faction §r§f§l<§r§4Administrator§r§f§l> \n §r§4$name");
					$player->setScoreTag("§r§f$health §r§4§lHP");
				}*/
			}
		}
	}
}