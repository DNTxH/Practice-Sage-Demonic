<?php
namespace vale\sage\demonic\listeners;

use vale\sage\demonic\join\JoinedTask;
use vale\sage\demonic\join\JoinListener;
use vale\sage\demonic\listeners\types\CooldownListener;
use vale\sage\demonic\listeners\types\SetsBonusesListener;
use vale\sage\demonic\Loader;
use vale\sage\demonic\rewards\RewardsListener;

class ListenerRegistry{

	public function __construct(
		private Loader $plugin
	){
		$this->init();
	}

	public function init(): void{
		$registery = Loader::getInstance()->getServer()->getPluginManager();
		#$registery->registerEvents(new JoinListener($this->plugin),$this->plugin);
		$registery->registerEvents(new RewardsListener($this->plugin), $this->plugin);
		$registery->registerEvents(new SetsBonusesListener($this->plugin),$this->plugin);
		$registery->registerEvents(new CooldownListener($this->plugin),$this->plugin);
		#new JoinedTask($this->getLoader());
	}

	public function getLoader(): ?Loader{
		return $this->plugin;
	}
}