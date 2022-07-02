<?php

namespace vale\sage\demonic\tasks\types;

use pocketmine\player\Player;
use pocketmine\scheduler\Task;
use vale\sage\demonic\Loader;

class CombatTagTask extends Task {

	/** @var Player */
	protected $player;

	/**
	 * CombatTagTask Constructor.
	 * @param Player $player
	 */
	public function __construct(Player $player){
		$this->player = $player;
		$session = Loader::getInstance()->getSessionManager()->getSession($player);
		$session->combatTag(true);
		$player->sendMessage("§r§c§l(!) §r§cYou have entered combat. Do not log out for 35s.");
	}

	/**
	 * @return void
	 */
	public function onRun() : void {
		$player = $this->player;
		$session = Loader::getInstance()->getSessionManager()->getSession($player);
		if(!$player->isOnline()){
			$this->getHandler()->cancel();
			return;
		}		if($player->isFlying()){

			$player->setAllowFlight(false);
			$player->setFlying(false);
			Loader::playSound($player,"firework.blast");
			return;
		}
		if(!$session->isCombatTagged()){
			$this->getHandler()->cancel();
			return;
		}
		if($session->combatTagTime() === 0){
			$player->setAllowFlight(true);
			$session->setCombatTagged(false);
			$player->sendMessage("§r§a§l(!) §r§aYou have left combat. You may now safely logout.");
			$this->getHandler()->cancel();
		}else{
			$session->setCombatTagTime($session->combatTagTime() - 1);
		}
	}
}
