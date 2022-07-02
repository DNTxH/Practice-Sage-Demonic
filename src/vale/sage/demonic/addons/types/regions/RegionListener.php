<?php

namespace vale\sage\demonic\addons\types\regions;

use pocketmine\block\BlockLegacyIds;
use pocketmine\event\block\BlockBreakEvent;
use pocketmine\event\block\BlockPlaceEvent;
use pocketmine\event\entity\EntityDamageEvent;
use pocketmine\event\entity\ProjectileLaunchEvent;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerExhaustEvent;
use pocketmine\event\player\PlayerInteractEvent;
use pocketmine\event\player\PlayerMoveEvent;
use pocketmine\event\player\PlayerToggleFlightEvent;
use pocketmine\network\mcpe\protocol\LevelSoundEventPacket;
use pocketmine\player\Player;
use pocketmine\scheduler\ClosureTask;
use pocketmine\Server;
use pocketmine\utils\TextFormat;
use vale\sage\demonic\Loader;

class RegionListener implements Listener
{

	/** @var Loader */
	private Loader $core;

    /** @var array */
	public array $cache = [];

    /** @var array */
    public array $spawn = [];

    /** @var array */
    public array $wilderness = [];

	/**
	 * AreaListener constructor.
	 *
	 * @param Loader $core
	 */
	public function __construct(Loader $core)
	{
		$this->core = $core;
	}

    /**
     * @param PlayerMoveEvent $event
     * @return void
     */
	public function onMove(PlayerMoveEvent $event)
	{
		$player = $event->getPlayer();
		$areaManager = Loader::getInstance()->getRegionManager();
		$areas = $areaManager->getAreasInPosition($player->getPosition()->asPosition());
		$session = Loader::getInstance()->getSessionManager()->getSession($player);
		if ($event->isCancelled()) {
			return;
		}
		if ($areas !== null) {
			foreach ($areas as $area) {
				if ($area->getName() === "Spawn" && !isset($this->spawn[$player->getName()])) {
					$player->setFlying(true);
					$player->setAllowFlight(true);
					$this->spawn[$player->getName()] = $player;
					$player->sendMessage(TextFormat::colorize("&r&a ~ Safezone - PvP is disabled here."));
					$player->sendTitle("§r§2§lPVP-DISABLED AREA");
					$player->sendSubTitle("§r§2§l*** §r§aPvP is §r§2§lDISABLED §r§ahere. (SafeZone) §r§2§l***");
					return;
				}
			}
		}
		$areas = $areaManager->getAreasInPosition($player->getPosition()->asPosition());

		if ($areas === null) {
			if ($event->isCancelled()) {
				return;
			}
			$spawn = Loader::getInstance()->getServer()->getWorldManager()->getDefaultWorld()->getSpawnLocation();
			if (!isset($this->cache[$player->getName()])) {
				$this->cache[$player->getName()] = $player;
				$player->sendMessage(TextFormat::colorize("&r&4 ~ Warzone - PvP is enabled here."));
				$player->sendTitle("§r§4§lPVP-ENABLED AREA");
				$player->sendSubTitle("§r§4§l*** §r§cPvP is §r§4§lENABLED §r§chere. (Warzone) §r§4§l***");
				return;
			}
			if (!isset($this->wilderness[$player->getName()]) && $player->getLocation()->distance($spawn) > 320) {
				$this->wilderness[$player->getName()] = $player;
				$player->sendMessage(TextFormat::colorize("&r&2 ~ Wilderness - Its not safe to go alone."));
				$player->sendTitle("§r§4§lPVP-ENABLED AREA");
				$player->sendSubTitle("§r§4§l*** §r§cPvP is §r§4§lENABLED §r§chere. (Wilderness) §r§4§l***");
				return;
			}
		}
		$areas = $areaManager->getAreasInPosition($player->getPosition()->asPosition());
		if (isset($this->cache[$player->getName()])) {
			if ($areas !== null) {
				unset($this->spawn[$player->getName()]);
				unset($this->cache[$player->getName()]);
				unset($this->wilderness[$player->getName()]);
			}
		}
	}

	/**
	 * @param PlayerToggleFlightEvent $event
	 */
	public function onToggleFly(PlayerToggleFlightEvent $event)
	{
		$player = $event->getPlayer();
		if (!$player instanceof Player) {
			return;
		}
		$session = Loader::getInstance()->getSessionManager()->getSession($player);
		if (!$session->isCombatTagged() && $player->getAllowFlight() === false) {
			$player->setAllowFlight(true);
		}
		if (!$event->isFlying()) {
			$player->setFlying(true);
			Loader::playSound($player, "firework.blast");
		}
		if ($event->isFlying()) {
			$player->setFlying(false);
			Loader::playSound($player, "firework.launch");
		}
	}

    /**
     * @param BlockPlaceEvent $event
     * @return void
     */
	public function onPlace(BlockPlaceEvent $event): void
	{
		$player = $event->getPlayer();

		if (!$player instanceof Player) {
			return;
		}
		$areaManager = Loader::getInstance()->getRegionManager();
		$areas = $areaManager->getAreasInPosition($player->getPosition()->asPosition());
		if ($areas !== null) {
			$event->cancel();
		}
	}

	/**
	 * @priority LOWEST
	 * @param PlayerExhaustEvent $event
	 */
	public function onPlayerExhaust(PlayerExhaustEvent $event): void {
		$player = $event->getPlayer();
		if(!$player instanceof Player) {
			return;
		}
		$areaManager = Loader::getInstance()->getRegionManager();
		$areas = $areaManager->getAreasInPosition($player->getPosition()->asPosition());
		if($areas !== null) {
			foreach($areas as $area) {
				if($area->getPvpFlag() === false) {
					$event->cancel();
					return;
				}
			}
		}
	}


	/**
	 * @priority LOWEST
	 * @param EntityDamageEvent $event
	 */
	public function onEntityDamage(EntityDamageEvent $event): void {
		$entity = $event->getEntity();
		if(!$entity instanceof Player) {
			return;
		}
		$areaManager = Loader::getInstance()->getRegionManager();
		$areas = $areaManager->getAreasInPosition($entity->getPosition()->asPosition());
		if($areas !== null) {
			foreach($areas as $area) {
				if($area->getPvpFlag() === false) {
					$event->cancel();
					return;
				}
			}
		}
	}

	/**
	 * @param PlayerInteractEvent $event
	 */
	public function onOpenEnderChest(PlayerInteractEvent $event): void{
		$player = $event->getPlayer();
		$block = $event->getBlock();
		$session = Loader::getInstance()->getSessionManager()->getSession($player);
		if($block->getId() === BlockLegacyIds::ENDER_CHEST){
			$event->cancel();
			Loader::getInstance()->getScheduler()->scheduleDelayedTask(new ClosureTask(function () use ($player, $session): void {
				if ($player != null || $player->isOnline() || !$player->isClosed()) {
					$player->sendMessage("§r§7Checking your last save...");
					$session->openEnderchest();
				}
			}), 10);
		}
	}

	/**
	 * @priority LOWEST
	 * @param ProjectileLaunchEvent $event
	 */
	public function onProjectileLaunch(ProjectileLaunchEvent $event): void {
		$entity = $event->getEntity();
		if(!$entity instanceof Player) {
			return;
		}
		$areaManager = Loader::getInstance()->getRegionManager();
		$areas = $areaManager->getAreasInPosition($entity->getPosition()->asPosition());
		if($areas !== null) {
			foreach($areas as $area) {
				if($area->getPvpFlag() === false) {
					$event->cancel();
					return;
				}
			}
		}
	}
}