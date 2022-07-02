<?php
namespace vale\sage\demonic\addons\types\end;

use pocketmine\entity\Location;
use pocketmine\Server;
use pocketmine\world\Position;
use pocketmine\world\World;
use vale\sage\demonic\addons\AddonManager;
use vale\sage\demonic\addons\types\end\entity\EndEntity;
use vale\sage\demonic\Loader;

class EndManager{

	/** @var array $areas */
	private array $areas = [
		[137, 75, 147],
		[139,72,143]
	];

	public int $timeToSpawn = 20;

	public AddonManager $addonManager;

	public static EndManager $instance;

	public function __construct(AddonManager $addonManager){
		self::$instance = $this;
		$this->addonManager = $addonManager;
	}

	public function getAddonManager(): AddonManager{
		return $this->addonManager;
	}

	public function tick(): void{
		--$this->timeToSpawn;
		if($this->timeToSpawn <= 0){
			$this->timeToSpawn = rand(300,300);
			#Server::getInstance()->broadcastMessage("SPAWNED NEW ENTITY");
			#$this->spawnEntity();
		}
	}

	public static function getInstance(): self{
		return self::$instance;
	}

	/**
	 * Spawns Entitys
	 */
	public function spawnEntity(): void{
		foreach ($this->areas as $spawnPosition){
			$positon = new Position($spawnPosition[0], $spawnPosition[1], $spawnPosition[2], $this->getEndLevel());
			$entity = new EndEntity(new Location($positon->getX() + rand(1,4), $positon->getY() + rand(1,3), $positon->getZ(), $this->getEndLevel(),0,0),null);
			$entity->spawnToAll();
			Server::getInstance()->broadcastMessage("SPAWNED NEW ENTITY");
		}
	}

	/**
	 * @return World
	 */
	public function getEndLevel(): ?World{
		return Loader::getInstance()->getServer()->getWorldManager()->getWorldByName("world_the_end");
	}
}