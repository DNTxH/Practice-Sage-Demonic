<?php

declare(strict_types=1);

namespace vale\sage\demonic\provider\task;

use pocketmine\scheduler\Task;
use vale\sage\demonic\Loader;


class SavePlayerDataTask extends Task
{
	public function __construct(
		private Loader $core
	){}

	public function onRun(): void
	{
		$start = microtime(true);
		foreach ($this->core->getServer()->getOnlinePlayers() as $player) {
			$session = Loader::getInstance()->getSessionManager()->getSession($player);
			if ($session !== null && $player->isOnline() && $player->isAuthenticated()) {
				$session->save(true);
			}
		}

		$time = (microtime(true) - $start);
		$this->core->getLogger()->notice("[Auto Save] Save completed in " . ($time >= 1 ? round($time, 3) . "s" : round($time * 1000) . "ms"));
	}
}