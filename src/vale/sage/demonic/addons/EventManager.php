<?php
namespace vale\sage\demonic\addons;

use pocketmine\world\Position;
use vale\sage\demonic\addons\types\envoys\Envoy;
use vale\sage\demonic\addons\types\envoys\EnvoyUpdater;
use vale\sage\demonic\addons\types\envoys\EventBase;
use vale\sage\demonic\addons\types\envoys\events\GrindEvent;
use vale\sage\demonic\addons\types\envoys\events\JackPotEvent;
use vale\sage\demonic\addons\types\envoys\events\lms\LMSManager;
use vale\sage\demonic\addons\types\envoys\events\MiningEvent;
use vale\sage\demonic\Loader;
use vale\sage\demonic\tasks\types\EventTask;

class EventManager
{

    /** @var EventBase|null */
	public ?EventBase $eventBase = null;

    /** @var int $time */
    public int $time = 60;

    /** @var int */
    public $wait = 90;

    /**
     * @param Loader $plugin
     * @param MiningEvent|null $miningEvent
     * @param LMSManager|null $LMSManager
     * @param JackPotEvent|null $jackPotEvent
     */
	public function __construct(
		public Loader $plugin,
		private ?MiningEvent $miningEvent = null,
		private ?LMSManager $LMSManager = null,
		private ?JackPotEvent $jackPotEvent = null,
	)
	{
		$this->plugin->getScheduler()->scheduleRepeatingTask(new EventTask(), 20);
		$this->init();
	}

	public function init(): void
	{
		new GrindEvent($this, "GrindEvent");
		$this->miningEvent = new MiningEvent($this, "MiningEvent");
		new Envoy($this);
		new EnvoyUpdater(new Position(54, 182, 82, Loader::getInstance()->getServer()->getWorldManager()->getDefaultWorld()));
		$this->LMSManager = new LMSManager($this, "LMS");
		$this->jackPotEvent = new JackPotEvent($this, "JACKPOT");
	}

    /**
     * @return MiningEvent|null
     */
	public function getMiningEvent(): ?MiningEvent{
		return $this->miningEvent;
	}

    /**
     * @return LMSManager|null
     */
	public function getLMS(): ?LMSManager{
		return $this->LMSManager;
	}

    /**
     * @return JackPotEvent|null
     */
	public function getJackPotEvent(): ?JackPotEvent{
		return $this->jackPotEvent;
	}

    /**
     * @return Loader
     */
	public function getSage(): Loader
	{
		return $this->plugin;
	}

	/**
	 * SWITCHES THRU EVENTS
	 */
	public function shiftEvents(): void
	{
		--$this->time;
		--$this->wait;
		if($this->time <= 0 && $this->wait <= 0){
			if($this->miningEvent->isEnabled()){
				$this->miningEvent->disable();
				$this->miningEvent->setEnabled(false);
			}
			if($this->LMSManager->isEnabled()){
				$this->LMSManager->disable();
				$this->LMSManager->setEnabled(false);
			}
			$events = [
				$this->LMSManager,
				$this->miningEvent,
			];
			$all = $events[array_rand($events)];
				$all->setEnabled(true);
				$all->announce();
			    $this->time = mt_rand(500,1000);
				$this->wait = mt_rand(600,1700);
		}
	}
}