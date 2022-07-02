<?php

declare(strict_types=1);

namespace vale\sage\demonic\listeners\types;

use pocketmine\entity\effect\EffectInstance;
use pocketmine\entity\effect\VanillaEffects;
use pocketmine\event\entity\EntityDamageByEntityEvent;
use pocketmine\event\entity\EntityDamageEvent;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerItemConsumeEvent;
use pocketmine\event\player\PlayerItemUseEvent;
use pocketmine\item\ItemIds;
use pocketmine\player\Player;
use pocketmine\Server;
use vale\sage\demonic\Loader;
use vale\sage\demonic\tasks\types\CombatTagTask;


class CooldownListener implements Listener
{

	/** @var Loader $plugin */
	private Loader $plugin;

	/** @var array $pulseCooldown */
	public array $pulseCooldown = [];

	/** @var array $goldenapplecooldown */
	public static $goldenapplecooldown = [];

	public function __construct(Loader $plugin)
	{
		$this->plugin = $plugin;
	}

	public function onConsume(PlayerItemConsumeEvent $event): void
	{
		$plugin = $this->plugin;
		$item = $event->getItem();
		$player = $event->getPlayer();
		$session = Loader::getInstance()->getSessionManager()->getSession($player);
		if ($event->getItem()->getId() !== ItemIds::GOLDEN_APPLE) {
			return;
		}
		$session->addGoldenApplesEaten();
		if ($session->getGoldenApplesEaten() === 2) {
			Server::getInstance()->broadcastMessage("§r§e§l(!) §r§eYou are about to contract Golden Apple Sickness!");
			Server::getInstance()->broadcastMessage("§r§7Don't eat anymore golden apples for 30s!");
			self::$goldenapplecooldown[$player->getName()] = time();
			return;
		}
		if ($session->getGoldenApplesEaten() >= 3) {
			Server::getInstance()->broadcastMessage("§r§c§l(!) §r§cYou have golden apple sickness!");
			Server::getInstance()->broadcastMessage("§r§7Stop eating golden apples before it's to late!");
			$effect1 = new EffectInstance(VanillaEffects::POISON(), 20 * rand(1, 16), rand(1, 3));
			$effect = new EffectInstance(VanillaEffects::NAUSEA(), 20 * rand(1, 16), rand(1, 10));
			$player->getEffects()->add($effect);
			$player->getEffects()->add($effect1);
		}
	}

	public function onUse(PlayerItemUseEvent $event): void
	{
		$player = $event->getPlayer();
		$session = Loader::getInstance()->getSessionManager()->getSession($player);
		$hand = $player->getInventory()->getItemInHand();
		$tag = $hand->getNamedTag();
		$areaManager = Loader::getInstance()->getRegionManager();
		if ($tag->getString("emppulse", "") !== "") {
			if (!isset($this->pulseCooldown[$player->getName()])) {
				$areas = $areaManager->getAreasInPosition($player->getPosition()->asPosition());
				foreach ($areas as $area) {
					if ($area->getName() === "Spawn") {
						$player->sendMessage("You cannot use this inside spawn");
						return;
					}
				}
				$player->sendMessage("Pulse activated");
				$this->pulseCooldown[$player->getName()] = time();
				$event->cancel();
			} else {
				$time = time() - $this->pulseCooldown[$player->getName()];
				if ($time < 60) {
					$timer = 60 - $time;
					$player->sendMessage("§r§cYou cannot use this for another §r§c§l{$timer}§r§c seconds.");
					$event->cancel();
				} elseif ($this->pulseCooldown[$player->getName()] <= time()) {
					unset($this->pulseCooldown[$player->getName()]);
				}
			}
		}
	}



	/**
	 * @param EntityDamageEvent $event
	 */
	public function onEntityDamageEvent(EntityDamageEvent $event): void
	{
		$player = $event->getEntity();
		if ($event instanceof EntityDamageByEntityEvent) {
			$damager = $event->getDamager();
			if ($event->getCause() === EntityDamageEvent::CAUSE_ENTITY_ATTACK || $event->getCause() === EntityDamageEvent::CAUSE_PROJECTILE) {
				if ($player instanceof Player && $damager instanceof Player) {
					$psession = Loader::getInstance()->getSessionManager()->getSession($player);
					$dsession = Loader::getInstance()->getSessionManager()->getSession($damager);
					if(!$psession->isCombatTagged()){
						Loader::getInstance()->getScheduler()->scheduleRepeatingTask(new CombatTagTask($player),20);
						$psession->setCombatTagged(true);
					}
					if(!$dsession->isCombatTagged()){
						Loader::getInstance()->getScheduler()->scheduleRepeatingTask(new CombatTagTask($damager),20);
						$dsession->setCombatTagged(true);
					}
					if($psession->isCombatTagged()){
						$psession->combatTag(true);
					}
					if($dsession->isCombatTagged()){
						$dsession->combatTag(true);
					}
				}
			}
		}
	}
}