<?php

declare(strict_types = 1);


namespace vale\sage\demonic\commands\types\mod;

use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\console\ConsoleCommandSender;
USE pocketmine\player\Player;
use pocketmine\Server;
use vale\sage\demonic\addons\types\envoys\Envoy;
use vale\sage\demonic\Loader;

use vale\sage\demonic\tasks\types\StopTask;


class StopCommand extends Command{

	/**
	 * StopCommand constructor.
	 * @param Loader $plugin
	 */

	public function __construct(Loader $plugin){
		parent::__construct("stop","","ok",[]);
	}

	/**
	 * @param CommandSender $sender
	 * @param string $label
	 * @param array $args
	 *
	 * @return bool
	 */

	public function execute(CommandSender $issuer, string $label, array $args) {
		if(Loader::getInstance()->getServer()->isOp($issuer->getName()) && $issuer instanceof ConsoleCommandSender || $issuer instanceof Player){
			Loader::getInstance()->getScheduler()->scheduleRepeatingTask(new StopTask(Loader::getInstance()), 20);
			Envoy::getInstance()->removeSkyDrops();
		}
	}
}