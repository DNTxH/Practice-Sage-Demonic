<?php


namespace vale\sage\demonic\enchants\enchantments\type\mastery;

use vale\sage\demonic\enchants\CustomEnchantIds;
use vale\sage\demonic\GenesisPlayer;
use vale\sage\demonic\Loader;
use vale\sage\demonic\enchants\CustomEnchant;
use pocketmine\item\Durable;
use pocketmine\item\enchantment\ItemFlags;
use pocketmine\player\Player;
use pocketmine\utils\TextFormat as C;
use pocketmine\network\mcpe\protocol\PlaySoundPacket;

use pocketmine\event\entity\EntityDamageByEntityEvent;

class RotAndDecay extends CustomEnchant {
	
	public function __construct() {
		parent::__construct(
			"Rot and Decay",
            CustomEnchantIds::ROTANDDECAY,
			"Procs on incoming damage. Huge chance of taking enemy's weapon / armor durability in large quantities.",
			10,
			ItemFlags::ARMOR,
			self::MASTERY,
			self::DEFENSIVE,
            self::ENTITY_DAMAGE_BY_ENTITY,
            self::ARMOR
		);

		$this->callable = function(EntityDamageByEntityEvent $event, int $level) {
			$entity = $event->getEntity();
			$damager = $event->getDamager();
            if (!$damager instanceof GenesisPlayer || !$entity instanceof GenesisPlayer) return;
			if (rand(1, 100) <= (3 * $level)) {
				$item = $entity->getInventory()->getItemInHand();
				if ($item instanceof Durable) {
					$item->applyDamage($dmg = rand(4, 20));
					$entity->getInventory()->setItemInHand($item);
					$entity->sendMessage(C::RED . "Your item has partially decayed from the enemy's Soul Siphon!");
					$entity->sendMessage(Loader::REG_CMD_PREFIX . "Your rotten armor has decayed enemy's item by " . $dmg . "pt!");
				}
				$armors = $damager->getArmorInventory()->getContents();
				$armor = $armors[array_rand($armors)];
				if ($armor instanceof Durable) {
					$armor->applyDamage($dmg = rand(5, 35));
					$damager->getArmorInventory()->setItem($armor->getArmorSlot(), $armor);
					$damager->sendMessage(C::RED . "Your " . $armor->getVanillaName() . " has partially decayed from enemy's rotten armor.");
					$entity->sendMessage(Loader::REG_CMD_PREFIX . "You have decayed enemy's " . $armor->getVanillaName() . " by " . $dmg . "pt!");
				}
				$this->sound($damager);
				$this->sound($entity);
			}
		};
	}

    /**
     * @param Player $player
     * @return void
     */
	public function sound(Player $player) {
		$packet = new PlaySoundPacket();
		$packet->soundName = "step.slime";
		$packet->x = $player->getPosition()->getX();
		$packet->y = $player->getPosition()->getY();
		$packet->z = $player->getPosition()->getZ();
		$packet->volume = 1;
		$packet->pitch = 1;
		$player->getNetworkSession()->sendDataPacket($packet);
	}
}