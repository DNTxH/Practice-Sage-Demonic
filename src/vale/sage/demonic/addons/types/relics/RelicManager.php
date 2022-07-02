<?php
namespace vale\sage\demonic\addons\types\relics;

use pocketmine\entity\EntityDataHelper;
use pocketmine\entity\EntityFactory;
use pocketmine\nbt\tag\CompoundTag;
use pocketmine\world\World;
use vale\sage\demonic\addons\AddonManager;
use vale\sage\demonic\addons\types\relics\types\OrbRelic;
use vale\sage\demonic\addons\types\relics\types\UndiscoveredMeteor;

class RelicManager{

    /**
     * @param AddonManager $addonManager
     */
	public function __construct(
		private AddonManager $addonManager
	){
		$this->init();
	}


	public function init(): void{
		$entityFactory = EntityFactory::getInstance();
		$entityFactory->register(UndiscoveredMeteor::class, function (World $world, CompoundTag $nbt): UndiscoveredMeteor {
			return new UndiscoveredMeteor(EntityDataHelper::parseLocation($nbt, $world), null);
		}, ["Relic"]);
     new RelicListener($this->addonManager);
	}

    /**
     * @return AddonManager|null
     */
	public function getAddonManager(): ?AddonManager{
		return $this->addonManager;
	}
}