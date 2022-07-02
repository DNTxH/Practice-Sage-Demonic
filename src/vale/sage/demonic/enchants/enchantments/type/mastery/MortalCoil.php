<?php


namespace vale\sage\demonic\enchants\enchantments\type\mastery;

use vale\sage\demonic\enchants\CustomEnchantIds;
use vale\sage\demonic\enchants\enchantments\EnchantmentsManager;
use vale\sage\demonic\GenesisPlayer;
use vale\sage\demonic\Loader;
use vale\sage\demonic\enchants\CustomEnchant;
use vale\sage\demonic\enchants\utils\IndicatorManager;
use pocketmine\item\enchantment\ItemFlags;
use pocketmine\utils\TextFormat as C;
use pocketmine\scheduler\ClosureTask;

use pocketmine\event\entity\EntityDamageByEntityEvent;

class MortalCoil extends CustomEnchant {

    /** @var array */
	private array $tagged;
	
	public function __construct() {
		parent::__construct(
			"Mortal Coil",
            CustomEnchantIds::MORTALCOIL,
			"This armor enchant procs on outgoing damage (you damaging another player) and causes players overload hearts to be reduced up to 5 for a duration of 6 seconds.",
			5,
			ItemFlags::ARMOR,
			self::MASTERY,
			self::OFFENSIVE,
            self::ENTITY_DAMAGE_BY_ENTITY,
            self::ARMOR
		);

		$this->callable = function(EntityDamageByEntityEvent $event, int $level) {
			$entity = $event->getEntity();
			$damager = $event->getDamager();
			$amplifier = ($level * 2);
			if (($entity->getMaxHealth() - 20) > $amplifier) {
				if (isset($this->tagged[$entity->getName()])) return;
                if (!$damager instanceof GenesisPlayer || !$entity instanceof GenesisPlayer) return;
				$color = C::BOLD.EnchantmentsManager::getColor($this);
				IndicatorManager::addTag($damager, $entity, "MORTAL COIL", 6, $color);
				
				$damager->sendMessage(C::BOLD.C::GREEN."***MORTAL COIL***");
				$entity->sendMessage(C::BOLD.C::RED."***MORTAL COIL***");
				$this->tagged[$entity->getName()] = 69420;
				$entity->setMaxHealth($entity->getMaxHealth() - $amplifier);
				Loader::getInstance()->getScheduler()->scheduleDelayedTask(new ClosureTask(function() use ($entity, $amplifier) {
					unset($this->tagged[$entity->getName()]);
					$entity->setMaxHealth($entity->getMaxHealth() + $amplifier);
					$entity->sendMessage(C::BOLD.C::GREEN."You've recovered the damage from Mortal Coil!");
				}), 120);
			}
		};
	}
}