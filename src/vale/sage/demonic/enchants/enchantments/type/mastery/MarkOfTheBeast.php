<?php


namespace vale\sage\demonic\enchants\enchantments\type\mastery;

use vale\sage\demonic\enchants\CustomEnchantIds;
use vale\sage\demonic\Loader;
use vale\sage\demonic\enchants\CustomEnchant;
use pocketmine\item\enchantment\ItemFlags;
use pocketmine\player\Player;
use pocketmine\Server;
use pocketmine\utils\TextFormat as C;
use pocketmine\scheduler\ClosureTask;
use pocketmine\event\Listener;
use pocketmine\event\entity\EntityDamageEvent;
use pocketmine\event\entity\EntityDamageByEntityEvent;

class MarkOfTheBeast extends CustomEnchant implements Listener {

	private int $maxLevel = 5;
	
	private array $tagged;
	
	public function __construct() {
        Server::getInstance()->getPluginManager()->registerEvents($this, Loader::getInstance());

		parent::__construct(
			"Mark of the Beast",
            CustomEnchantIds::MARKOFTHEBEAST,
			"Once an enemy is afflicted with the mark, all incoming damage is increased by 2x for up to 5 seconds.",
			5,
			ItemFlags::SWORD,
			self::MASTERY,
			self::OFFENSIVE,
            self::ENTITY_DAMAGE_BY_ENTITY,
            self::SWORD
		);

		$this->callable = function(EntityDamageByEntityEvent $event, int $level) {
			$entity = $event->getEntity();
			$damager = $event->getDamager();
			if (rand(1, 100) <= (4 * $level)) {
				if (isset($this->tagged[$entity->getName()])) return;
				$entity->sendMessage(C::BOLD.C::RED."***MARK OF THE BEAST***");
				$damager->sendMessage(C::BOLD.C::GREEN."***MARK OF THE BEAST***");
				$this->tagged[$entity->getName()] = true;
				
				Loader::getInstance()->getScheduler()->scheduleDelayedTask(new ClosureTask(function() use ($entity, $level) {
					if (!isset($this->tagged[$entity->getName()])) return;
					unset($this->tagged[$entity->getName()]);
				}), 20 * $level);
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
			if (isset($this->tagged[$entity->getName()])) {
				$event->setBaseDamage($event->getBaseDamage() * 2);
			}
		}
	}
}