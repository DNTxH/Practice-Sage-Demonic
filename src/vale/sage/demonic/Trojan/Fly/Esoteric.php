<?php

namespace  vale\sage\demonic\Trojan\Fly;

use  vale\sage\demonic\Trojan\Fly\data\PlayerData;
use  vale\sage\demonic\Trojan\Fly\data\PlayerDataManager;
use  vale\sage\demonic\Trojan\Fly\listener\EsotericEventListener;
use  vale\sage\demonic\Trojan\Fly\protocol\PlayerAuthInputPacket;
use  vale\sage\demonic\Trojan\Fly\tasks\TickingTask;
use  vale\sage\demonic\Trojan\Fly\thread\LoggerThread;
use Exception;
use pocketmine\event\HandlerListManager;
use pocketmine\network\mcpe\protocol\PacketPool;
use pocketmine\plugin\Plugin;
use pocketmine\plugin\PluginBase;
use pocketmine\utils\Config;
use const PTHREADS_INHERIT_NONE;

final class Esoteric{

	/** @var Esoteric|null */
	private static ?Esoteric $instance = null;

	/** @var bool */
	public bool $running = false;
	/** @var Plugin - Plugin that initialized Esoteric. */
	public Plugin $plugin;
	/** @var Settings */
	public Settings $settings;
	/** @var LoggerThread */
	public LoggerThread $logger;
	/** @var EsotericEventListener */
	public EsotericEventListener $listener;
	/** @var PlayerData[] */
	public array $hasAlerts = [];
	/** @var PlayerDataManager */
	public PlayerDataManager $dataManager;
	/** @var TickingTask */
	public TickingTask $tickingTask;

	/**
	 * Esoteric constructor.
	 *
	 * @param PluginBase $plugin
	 * @param Config     $config
	 */
	public function __construct(PluginBase $plugin, Config $config){
		$this->plugin = $plugin;
		$this->settings = new Settings($config->getAll());
		$this->logger = new LoggerThread($this->getPlugin()->getDataFolder() . "esoteric.log");
		$this->listener = new EsotericEventListener();
		$this->dataManager = new PlayerDataManager();
		$this->tickingTask = new TickingTask();
	}

	/**
	 * @param PluginBase $plugin - Plugin to initialize Esoteric.
	 * @param Config     $settings - Configuration for Esoteric.
	 * @param bool       $start - If Esoteric should start after initialization.
	 *
	 * @throws Exception
	 */
	public static function init(PluginBase $plugin, Config $settings, bool $start = false) : void{
		if(self::$instance !== null){
			throw new Exception("Esoteric has already been initialized by " . self::$instance->plugin->getName());
		}
		self::$instance = new self($plugin, $settings);
		if($start){
			self::$instance->start();
		}
	}

	public function start() : void{
		if($this->running){
			return;
		}

		$this->logger->start(PTHREADS_INHERIT_NONE);
		$this->plugin->getServer()->getPluginManager()->registerEvents($this->listener, $this->plugin);
		$this->plugin->getScheduler()->scheduleRepeatingTask($this->tickingTask, 1);

		// TODO: Remove PlayerAuthInputPacket override when BedrockProtocol gets updated
		PacketPool::getInstance()->registerPacket(new PlayerAuthInputPacket());

		$this->running = true;
        $this->plugin->getServer()->getLogger()->info("Esoteric has been enabled!");
	}

	public function getPlugin() : PluginBase{
		return $this->plugin;
	}

	/**
	 * @return Esoteric|null
	 */
	public static function getInstance() : ?self{
		return self::$instance;
	}

	public function stop() : void{
		$this->logger->quit();
		HandlerListManager::global()->unregisterAll($this->listener);
		$this->tickingTask->getHandler()?->cancel();

		$this->running = false;
	}

	public function getSettings() : Settings{
		return $this->settings;
	}

}