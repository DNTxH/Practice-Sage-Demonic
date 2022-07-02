<?php
namespace vale\sage\demonic\addons\types\envoys\events;

use pocketmine\event\block\BlockBreakEvent;
use vale\sage\demonic\addons\EventManager;
use vale\sage\demonic\addons\types\envoys\EventBase;

class GrindEvent extends EventBase{

    /** @var self */
    private static $instance;

    /**
     * @param EventManager $eventManager
     * @param $eventName
     */
	public function __construct(EventManager $eventManager, private $eventName)
	{
        self::$instance = $this;
		$this->eventManager = $eventManager;
		#$this->eventName = $this->eventName;
		parent::__construct($eventManager, "GrindEvent");
	}

    /**
     * @param BlockBreakEvent $event
     * @return void
     */
	public function onBlockBreakEvent(BlockBreakEvent $event): void
	{
		$player = $event->getPlayer();
	}

	/**
	 * @return EventBase
	 */
	public static function getInstance(): EventBase
	{
		return self::$instance;
	}

}