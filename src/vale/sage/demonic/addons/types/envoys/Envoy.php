<?php
namespace vale\sage\demonic\addons\types\envoys;
use pocketmine\block\Chest;
use pocketmine\block\VanillaBlocks;
use pocketmine\entity\Location;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerInteractEvent;
use pocketmine\math\Vector2;
use pocketmine\math\Vector3;
use pocketmine\player\Player;
use pocketmine\Server;
use pocketmine\world\particle\BlockBreakParticle;
use pocketmine\world\particle\FloatingTextParticle;
use pocketmine\world\Position;
use pocketmine\world\World;
use vale\sage\demonic\addons\EventManager;
use vale\sage\demonic\entitys\types\EnvoyDemon;
use vale\sage\demonic\entitys\types\TextEntity;
use vale\sage\demonic\rewards\Rewards;

class Envoy implements Listener
{

	public const PREFIX = "§r§f§l*** §r§6§lSAGE ENVOY §r§f§l*** \n";

    /** @var int */
	public int $time = 400;

    /** @var int */
	public int $despawn = 300;

    /** @var int */
	public $lastenvoy;

	/** @var array $areas */
	private array $areas = [
		[167, 81, 68],
		[130,78,30],
	];

    /** @var array */
	private array $skyDrops = [];

    /** @var Envoy */
	private static Envoy $instance;

    /** @var bool */
	public bool $enabled = false;

    /** @var FloatingTextParticle */
	public FloatingTextParticle $textParticle;

    /**
     * @param EventManager $eventManager
     */
	public function __construct(
		private EventManager $eventManager
	)
	{
		$this->lastenvoy = time();
		self::$instance = $this;
		$this->getEventManager()->getSage()->getServer()->getPluginManager()->registerEvents($this, $this->eventManager->getSage());
	}

    /**
     * @return static
     */
	public static function getInstance(): self
	{
		return self::$instance;
	}

    /**
     * @return string
     */
	public function getName(): string{
		return "Envoy";
	}

	public function tick(): void
	{
		$world = $this->getEventManager()->getSage()->getServer()->getWorldManager()->getDefaultWorld();
		if(!$this->isEnabled() && $this->time >= 0){
			--$this->time;
		}
		if($this->isEnabled() && $this->despawn >= 0){
			--$this->despawn;
		}
		if($this->isEnabled() && $this->despawn <= 0){
			$this->setEnabled(false);
			$this->despawn = rand(1,1000);
			$this->time = rand(1,1000);
			$this->removeSkyDrops();
		}
		if ($this->time <= 0 && !$this->isEnabled()){
			$this->setEnabled(true);
			$this->lastenvoy = time();
			Server::getInstance()->broadcastMessage(self::PREFIX . "    §r§fA §r§6§lSage Envoy §r§fhas appeared underneath the main /spawn supply crates can be seen falling all over the §r§6§lWarzone! \n §r§f(( §r§7Chests filled with random tier loot have spawned at 50-100 random locations throughout the planet's warzone go get some loot! Remember, \n §r§7more chests spawn when there are more players. §r§f))");
			foreach ($this->areas as $position) {
				Server::getInstance()->getWorldManager()->getDefaultWorld()->loadChunk($position[0], $position[2]);
				$this->spawnSkyDrop(new Position($position[0], $position[1], $position[2], $world));
			}
		}
	}

    /**
     * @param int $newTime
     * @return void
     */
	public function setTime(int $newTime): void{
		$this->time = (int) $newTime;
	}

    /**
     * @return int|null
     */
	public function getLastEnovyTime(): ?int
	{
		return $this->lastenvoy;
	}

    /**
     * @param bool $enabled
     * @return void
     */
	public function setEnabled(bool $enabled): void{
		$this->enabled = $enabled;
	}

    /**
     * @return bool
     */
	public function isEnabled(): bool{
		return $this->enabled;
	}

	/**
	 * @param PlayerInteractEvent $event
	 */
	public function tap(PlayerInteractEvent $event)
	{
		$player = $event->getPlayer();
		$block = $event->getBlock();
		if (
			in_array($block->getPosition(), $this->skyDrops)) {
			if ($block instanceof Chest) {
				$position = $block->getPosition();
				$event->cancel();
				$position->getWorld()->addParticle($position, new BlockBreakParticle(VanillaBlocks::CHEST()));
				$position->getWorld()->setBlock($position, VanillaBlocks::AIR());
				$position->getWorld()->dropItem($position,Rewards::get(rand(1,5),rand(1,4)));
				$e = new EnvoyDemon(new Location($position->getX(), $position->getY(), $position->getZ(),$position->getWorld(),0,0));
				$e->spawnToAll();
				unset($this->skyDrops[array_search($block->getPosition(), $this->skyDrops)]);
			}
		}
	}



	/**
	 * Spawns a SkyDrop at a Given Position
	 * @param Position $position
	 */
	public function spawnSkyDrop(Position $position)
	{
		if (!$position->getWorld()->isLoaded()) {
			$this->getEventManager()->getSage()->getServer()->getWorldManager()->loadWorld($position->getWorld()->getFolderName());
		}
		$position->getWorld()->setBlockAt($position->getX(), $position->getY(), $position->getZ(), VanillaBlocks::CHEST());
		array_push($this->skyDrops, $position);
	}

	public function removeSkyDrops(): void
	{
		$world = $this->getEventManager()->getSage()->getServer()->getWorldManager()->getDefaultWorld();
		foreach ($this->areas as $position) {
			$position = new Position($position[0], $position[1], $position[2], $world);
			$position->getWorld()->setBlockAt($position->getX(), $position->getY(), $position->getZ(), VanillaBlocks::AIR());
		}
	}

	/**
	 * @return int
	 */
	public function getSkyDrops(): int{
		return count($this->skyDrops);
	}

	/**
	 * @return EventManager
	 */
	public function getEventManager(): EventManager{
		return  $this->eventManager;
	}

}