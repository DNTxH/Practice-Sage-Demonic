<?php


namespace vale\sage\demonic\enchants\enchantments\type\mastery;

use vale\sage\demonic\Loader;
use vale\sage\demonic\enchants\CustomEnchant;
use vale\sage\demonic\enchants\CustomEnchantIds;
use vale\sage\demonic\enchants\utils\IndicatorManager;
use pocketmine\item\Item;
use pocketmine\item\enchantment\ItemFlags;
use pocketmine\player\Player;
use pocketmine\Server;
use pocketmine\utils\TextFormat as C;
use pocketmine\scheduler\ClosureTask;
use pocketmine\event\Listener;

use pocketmine\event\entity\EntityDamageEvent;
use pocketmine\event\entity\EntityDamageByEntityEvent;

class FeignDeath extends CustomEnchant implements Listener
{

    /** @var array */
	private array $tagged;
	
	public function __construct() {
		Server::getInstance()->getPluginManager()->registerEvents($this, Loader::getInstance());

		parent::__construct(
			"Feign Death",
            CustomEnchantIds::FEIGNDEATH,
			"Chance to fake death and vanish for up to 6 seconds, or until you attack a player.",
			4,
			ItemFlags::ARMOR,
			self::MASTERY,
			self::DEFENSIVE,
            self::ENTITY_DAMAGE_BY_ENTITY,
            self::ARMOR
		);

		$this->callable = function(EntityDamageByEntityEvent $event, int $level) {
			$entity = $event->getEntity();
			if (rand(1, 100) <= (2 * $level)) {
				if (isset($this->tagged[$entity->getName()])) return;
				foreach(Server::getInstance()->getOnlinePlayers() as $onlinePlayer){
					$onlinePlayer->hidePlayer($entity);

				}
				$entity->sendMessage(C::BOLD.C::GREEN."You have successfully faked your death and vanished.");
				$this->tagged[$entity->getName()] = true;
				
				Loader::getInstance()->getScheduler()->scheduleDelayedTask(new ClosureTask(function() use ($entity) {
					if (!isset($this->tagged[$entity->getName()])) return;
					$entity->sendMessage(C::BOLD.C::GREEN."You have unvanished.");
					unset($this->tagged[$entity->getName()]);
					foreach(Server::getInstance()->getOnlinePlayers() as $onlinePlayer){
						$onlinePlayer->showPlayer($entity);
					}
				}), 120);
			}
		};
	}

    /**
     * @param EntityDamageEvent $event
     * @return void
     */
	public function onDamage(EntityDamageEvent $event) {
        if (!$event instanceof EntityDamageByEntityEvent) return;
        $entity = $event->getEntity();
        $damager = $event->getDamager();
        if ($event->isCancelled()) return;
        if ($damager instanceof Player && $entity instanceof Player) {
			if (!isset($this->tagged[$entity->getName()])) return;
			unset($this->tagged[$entity->getName()]);
			
			$entity->sendMessage(C::BOLD.C::GREEN."You have unvanished.");
		}
	}
}