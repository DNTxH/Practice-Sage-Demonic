<?php


namespace vale\sage\demonic\enchants\enchantments\type\mastery;

use vale\sage\demonic\enchants\CustomEnchantIds;
use vale\sage\demonic\GenesisPlayer;
use vale\sage\demonic\Loader;
use vale\sage\demonic\enchants\CustomEnchant;
use vale\sage\demonic\enchants\utils\SoulPoint;
use pocketmine\item\Durable;
use pocketmine\item\enchantment\ItemFlags;
use pocketmine\player\Player;
use pocketmine\utils\TextFormat as C;
use pocketmine\network\mcpe\protocol\PlaySoundPacket;

use pocketmine\event\entity\EntityDamageByEntityEvent;

class SoulSiphon extends CustomEnchant
{

	private int $maxLevel = 4;
	
	public function __construct() {
		parent::__construct(
			"Soul Siphon",
            CustomEnchantIds::SOULSIPHON,
			"Procs on outgoing damage. Chance to obtain soul points with upto 20% chance and drains durability from enemies in large quantities.",
			4,
			ItemFlags::SWORD,
			self::MASTERY,
			self::OFFENSIVE,
            self::ENTITY_DAMAGE_BY_ENTITY,
            self::SWORD
		);

		$this->callable = function(EntityDamageByEntityEvent $event, int $level) {
			$entity = $event->getEntity();
			$damager = $event->getDamager();
            if (!$damager instanceof GenesisPlayer || !$entity instanceof GenesisPlayer) return;
			
			if (rand(1, 100) <= (5 * $level)) {
				$item = $damager->getInventory()->getItemInHand();
				if (SoulPoint::hasTracker($item)) {
					$add = rand(1, 9);
					$souls = SoulPoint::getSoul($item);
					$damager->getInventory()->setItemInHand(SoulPoint::setSoul($item, $souls + $add));
					
					$damager->sendMessage(LOADER::REG_CMD_PREFIX . "You have obtained " . $add . " souls from your Soul Siphon!");
				}
				$item = $entity->getInventory()->getItemInHand();
				if ($item instanceof Durable) {
					$item->applyDamage(rand(5, 30));
					$entity->getInventory()->setItemInHand($item);
					$this->sound($entity);
					$entity->sendMessage(C::RED . "Your item has partially decayed from the enemy's Soul Siphon!");
				}
			}
		};
	}

    /**
     * @param Player $player
     * @return void
     */
	public function sound(Player $player) {
		$packet = new PlaySoundPacket();
		$packet->soundName = "random.break";
		$packet->x = $player->getPosition()->getX();
		$packet->y = $player->getPosition()->getY();
		$packet->z = $player->getPosition()->getZ();
		$packet->volume = 1;
		$packet->pitch = 1;
		$player->getNetworkSession()->sendDataPacket($packet);
	}
}