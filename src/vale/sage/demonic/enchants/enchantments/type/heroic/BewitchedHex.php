<?php

declare(strict_types = 1);

namespace vale\sage\demonic\enchants\enchantments\type\heroic;

use pocketmine\entity\effect\Effect;
use pocketmine\event\entity\EntityDamageByEntityEvent;
use pocketmine\item\enchantment\ItemFlags;
use pocketmine\player\Player;
use pocketmine\scheduler\ClosureTask;
use vale\sage\demonic\enchants\CustomEnchantIds;
use vale\sage\demonic\Loader;

class BewitchedHex extends HeroicCustomEnchant {

    /** @var array */
    private static array $hexed = [];

    public function __construct()
	{
		parent::__construct(
			"Bewitched Hex",
			CustomEnchantIds::BEWITCHEDHEX,
			"Once a target is afflicted with Bewitched Hex, a portion of all outgoing damage is reflected back onto them for up to 8 seconds",
			5,
			ItemFlags::AXE,
			self::HEROIC,
			self::OFFENSIVE,
			self::ENTITY_DAMAGE_BY_ENTITY,
			self::AXE,
			CustomEnchantIds::HEX
		);

		$this->callable = function (EntityDamageByEntityEvent $event, int $level): void {
			$entity = $event->getEntity();
			$damager = $event->getDamager();

			if ($event->isCancelled() || !$entity instanceof Player || !$damager instanceof Player) return;

			if (mt_rand(0, 100) <= $level * 3 && !isset(self::$hexed[$entity->getUniqueId()->toString()])) {
				self::$hexed[$entity->getUniqueId()->toString()] = $level;

				Loader::getInstance()->getScheduler()->scheduleDelayedTask(new ClosureTask(function () use ($entity): void {
					unset(self::$hexed[$entity->getUniqueId()->toString()]);
				}), $level * 32);
			}
		};
	}

        /**
         * @param EntityDamageByEntityEvent $event
         * @return void
         */
        public function onDamage(EntityDamageByEntityEvent $event) : void {
            $entity = $event->getEntity();
            $damager = $event->getDamager();

            if($event->isCancelled() || !$entity instanceof Player || !$damager instanceof Player) return;

            if(!isset(self::$hexed[$damager->getUniqueId()->toString()])) return;

            $damager->setHealth($damager->getHealth() - (self::$hexed[$damager->getUniqueId()->toString()] * ($event->getFinalDamage() / 10)));
        }

}