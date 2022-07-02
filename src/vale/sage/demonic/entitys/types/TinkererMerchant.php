<?php

declare(strict_types = 1);

namespace vale\sage\demonic\entitys\types;

use pocketmine\entity\Human;
use pocketmine\entity\Location;
use pocketmine\entity\Skin;
use pocketmine\event\entity\EntityDamageByEntityEvent;
use pocketmine\event\entity\EntityDamageEvent;
use pocketmine\event\player\PlayerEntityInteractEvent;
use pocketmine\item\ItemFactory;
use pocketmine\item\ItemIds;
use pocketmine\nbt\tag\CompoundTag;
use pocketmine\player\Player;
use pocketmine\world\World;
use vale\sage\demonic\addons\types\tinkerer\TinkerInventory;
use vale\sage\demonic\kits\inventory\KitsInventory;
use vale\sage\demonic\Loader;

class TinkererMerchant extends Human
{

	const NETWORK_ID = 1;

	/**
	 * BaseEntity constructor.
	 *
	 * @param World $level
	 * @param CompoundTag $nbt
	 * @param Player $player
	 *
	 */
	public function __construct(Location $location, ?Skin $skin = null, ?CompoundTag $nbt = null)
	{
		parent::__construct($location, $skin, $nbt);
		$this->setMaxHealth(4);
		$this->setNameTag(self::getNPCName());
		$this->setNameTagAlwaysVisible(true);
		$this->setHealth(4);
		$this->setScale(1);
		$this->location->yaw = $this->getLocation()->getYaw();
		$this->getInventory()->setItemInHand(ItemFactory::getInstance()->get(ItemIds::ENCHANTED_BOOK));
		$this->setCanSaveWithChunk(true);
	}

	/**
	 * @return string
	 */
	public function getName(): string
	{
		return "aTinkerer";
	}


	public function attack(EntityDamageEvent $source): void
	{
		if ($source instanceof EntityDamageByEntityEvent) {
			$damager = $source->getDamager();
			if ($damager instanceof Player) {
				TinkerInventory::open($damager);
			}
		}
	}

	public static function getNPCName(): string
	{
		$line = [
			"§r§6§lSage Tinkerer ",
			str_repeat(" ", 3),
			"\n§r§f0 §r§4§lHP",
			"\n§r§7(Click to open Interface)\n\n",
			"\n",
			"\n",
		];
		#foreach($val as $line){

		return ($line[0] . "\n" . $line[1] . $line[2] . $line[3] . $line[4] . $line[5]);
		# }
	}
}


