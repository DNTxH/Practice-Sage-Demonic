<?php
namespace vale\sage\demonic\addons;

use vale\sage\demonic\addons\types\end\EndManager;
use vale\sage\demonic\addons\types\relics\RelicManager;
use vale\sage\demonic\addons\types\broadcast\BroadcastManager;
use vale\sage\demonic\addons\types\monthlycrates\MonthlyCrates;
use vale\sage\demonic\Loader;

class AddonManager{

    /**
     * @param Loader $plugin
     * @param MonthlyCrates|null $monthlyCrates
     * @param BroadcastManager|null $broadcastManager
     * @param EventManager|null $eventManager
     * @param RelicManager|null $relicManager
     * @param EndManager|null $endManager
     */
	public function __construct(
		private Loader  $plugin,
		private ?MonthlyCrates $monthlyCrates = null,
		private ?BroadcastManager $broadcastManager = null,
		private ?EventManager $eventManager = null,
		private ?RelicManager $relicManager = null,
		private ?EndManager $endManager = null,
	){
		$this->init();
	}

	public function init(): void{
      $this->monthlyCrates = new MonthlyCrates();
	  $this->broadcastManager = new BroadcastManager($this);
	  $this->eventManager = new EventManager(Loader::getInstance());
	  $this->relicManager = new RelicManager($this);
	  $this->endManager = new EndManager($this);
	}

	/**
	 * @return EndManager|null
	 */
	public function getEndManager(): ?EndManager
	{
		return $this->endManager;
	}

	/**
	 * @return RelicManager|null
	 */
	public function getRelicManager(): ?RelicManager
	{
		return $this->relicManager;
	}

	/**
	 * @return BroadcastManager|null
	 */
	public function getBroadcastAddon(): ?BroadcastManager{
		return $this->broadcastManager;
	}

    /**
     * @return MonthlyCrates
     */
	public function getMonthlyCrates(): MonthlyCrates{
		return $this->monthlyCrates;
	}

    /**
     * @return EventManager|null
     */
	public function getEventManager(): ?EventManager{
		return $this->eventManager;
	}

    /**
     * @return Loader|null
     */
	public function getLoader(): ?Loader{
		return $this->plugin;
	}
}