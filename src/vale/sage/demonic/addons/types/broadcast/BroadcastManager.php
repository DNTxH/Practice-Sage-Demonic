<?php
namespace vale\sage\demonic\addons\types\broadcast;

use vale\sage\demonic\addons\AddonManager;
use vale\sage\demonic\addons\types\broadcast\task\BroadcastTask;
use vale\sage\demonic\addons\types\broadcast\task\TitlesTask;
use vale\sage\demonic\Loader;


class  BroadcastManager{

	public const PREFIX = "         \n                 §r§6§lSage §r§8| §r§e§lAnnouncer \n";
	public const SPACE = "    ";

    /**
     * @param AddonManager $manager
     * @param array|null $messages
     * @param array|null $titles
     */
	public function __construct(
		private AddonManager $manager,
		private ?array $messages = null,
		private ?array $titles = null,

	){
     $this->messages = [];
	 $this->manager->getLoader()->getScheduler()->scheduleRepeatingTask(new BroadcastTask($this),500);
	# $this->manager->getLoader()->getScheduler()->scheduleRepeatingTask(new TitlesTask($this),60);
	 $this->init();
	}

	public function init(): void{
		$this->addMessage(self::PREFIX . self::SPACE .  "    §r§fAre you a content content creator? \n          §r§fWant to join our §r§6§lmedia §r§fteam? \n          §r§fJoin our discord and §r§6apply.\n           §r§fin the §r§6#apply-channel§f.");
		$this->addMessage(self::PREFIX . self::SPACE .  "    §r§fFollow us on Twitter: §6@SagePvP");
		$this->addMessage(self::PREFIX . self::SPACE .  "    §r§fTo view a list of available  \n          §r§ffeatures run §r§6§l/features");
		$this->addMessage(self::PREFIX . self::SPACE .  "      §r§fUse §r§e/dispose §r§fto dispose  \n            §r§fof unwanted item(s).");
		$this->addMessage(self::PREFIX . self::SPACE .  "    §r§fTo purchase any available \n          §r§fcustom-enchants type §r§e§l/enchanter");
	}

    /**
     * @return AddonManager
     */
	public function getAddonManager(): AddonManager{
		return $this->manager;
	}

    /**
     * @return array
     */
	public function getMessages(): array{
		return $this->messages;
	}

    /**
     * @return array
     */
	public function getTitles(): array{
		return $this->titles;
	}
	/**
	 * @param string $message
	 */
	public function addTitle(string $message): void{
		$this->titles[] = $message;
	}

	/**
	 * @param string $message
	 */
	public function addMessage(string $message): void{
		$this->messages[] = $message;
	}
}