<?php
namespace vale\sage\demonic\tasks\types;

use pocketmine\scheduler\Task;
use vale\sage\demonic\addons\types\end\EndManager;
use vale\sage\demonic\addons\types\envoys\Envoy;
use vale\sage\demonic\addons\types\envoys\EnvoyUpdater;
use vale\sage\demonic\addons\types\envoys\events\JackPotEvent;
use vale\sage\demonic\addons\types\envoys\events\lms\LMSManager;
use vale\sage\demonic\Loader;

class EventTask extends Task{


	public function onRun(): void
	{
		$manager = Loader::getInstance()->getAddonManager();
		if($manager->getEventManager()->getLMS()->isEnabled()){
			$manager->getEventManager()->getLMS()->tick();
		}

		Loader::getInstance()->getAddonManager()->getEventManager()->shiftEvents();
		EnvoyUpdater::getInstance()->tick();
		Envoy::getInstance()->tick();
		EndManager::getInstance()->tick();
		$e = JackPotEvent::getInstance();
		$e->tick();
	}
}