<?php
namespace vale\sage\demonic\addons\types\monthlycrates;
use pocketmine\entity\EntityDataHelper;
use pocketmine\entity\EntityFactory;
use pocketmine\entity\Location;
use pocketmine\nbt\tag\CompoundTag;
use pocketmine\world\World;
use vale\sage\demonic\addons\types\monthlycrates\entity\EnderChestEntity;

class MonthlyCrates{

	public function __construct(){
		$this->register();
        // TODO ept here, no idea wtf is going on here, half of it was edited out enough to cause syntax error so i left it all for now
	}

	public function register(): void{
		$entityFactory = EntityFactory::getInstance();

		$entityFactory->register(EnderChestEntity::class, function (World $world, CompoundTag $nbt): EnderChestEntity {
			return new EnderChestEntity(EntityDataHelper::parseLocation($nbt, $world),new Location(0,0,0,$world,0,0));
		}, ["aEnder"]);
	}

	/**public function getMonthlyCrate(): ?Item{
		return \pocketmine\item\VanillaItems::DIAMOND_BOOTS();
		$entityFactory->register(MCEntity::class, function (World $world, CompoundTag $nbt): MCEntity {
			return new MCEntity(EntityDataHelper::parseLocation($nbt, $world),new Location(0,0,0,$world,0,0));
		}, ["aEntity"]);
	}*/
}