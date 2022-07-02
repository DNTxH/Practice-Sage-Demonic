<?php


namespace vale\sage\demonic\enchants\enchantments\type\mastery;

use vale\sage\demonic\enchants\CustomEnchantIds;
use vale\sage\demonic\enchants\projectile\GayWitherskull;
use vale\sage\demonic\GenesisPlayer;
use vale\sage\demonic\enchants\CustomEnchant;
use pocketmine\entity\Location;
use pocketmine\item\enchantment\ItemFlags;
use pocketmine\player\Player;
use pocketmine\network\mcpe\protocol\PlaySoundPacket;

use pocketmine\event\entity\EntityDamageByEntityEvent;

class DemonicGateway extends CustomEnchant {
	
	public function __construct() {
		parent::__construct(
			"Demonic Gateway",
            CustomEnchantIds::DEMONICGATEWAY,
			"Procs on outgoing damage. Fires a witherhead that inflicts wither effect, and has up to 2% of instantly destroying enemy's armor piece.",
            1,
			ItemFlags::SWORD,
			self::MASTERY,
			self::OFFENSIVE,
            self::ENTITY_DAMAGE_BY_ENTITY,
            self::SWORD
		);

		$this->callable = function(EntityDamageByEntityEvent $event, int $level) {
			$damager = $event->getDamager();
			if (rand(1, 100) <= 5) {
				$location = $damager->getLocation();
				$witherskull = new GayWitherskull(Location::fromObject(
					$damager->getEyePos(),
					$damager->getWorld(),
					($location->yaw > 180 ? 360 : 0) - $location->yaw,
					-$location->pitch
				), $damager, null);
				$witherskull->setMotion($damager->getDirectionVector());
				$witherskull->spawnToAll();

                if ($damager instanceof GenesisPlayer) $this->sound($damager);
			}
		};
	}

    /**
     * @param Player $player
     * @return void
     */
	public function sound(Player $player) {
		$packet = new PlaySoundPacket();
		$packet->soundName = "mob.enderdragon.hit";
		$packet->x = $player->getPosition()->getX();
		$packet->y = $player->getPosition()->getY();
		$packet->z = $player->getPosition()->getZ();
		$packet->volume = 1;
		$packet->pitch = 1;
		$player->getNetworkSession()->sendDataPacket($packet);
	}
}
