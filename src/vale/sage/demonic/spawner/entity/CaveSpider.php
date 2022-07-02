<?php

namespace vale\sage\demonic\spawner\entity;

use vale\sage\demonic\spawner\SpawnerBase;
use pocketmine\entity\EntitySizeInfo;
use pocketmine\item\VanillaItems;
use pocketmine\player\Player;
use pocketmine\network\mcpe\protocol\types\entity\EntityIds;
class CaveSpider extends SpawnerBase
{
	protected function getInitialSizeInfo() : EntitySizeInfo{
		return new EntitySizeInfo(0.5, 1);
	}
	
	public static function getNetworkTypeId() : string{
		return EntityIds::CAVE_SPIDER;
	}
	
	public function getName(): string{
        return "Cave Spider";
    }
	
	public function getDrops(): array{
		$drops = [
			VanillaItems::STRING()->setCount(mt_rand(0, 2)),
			VanillaItems::ROTTEN_FLESH()->setCount(mt_rand(0, 1))
		];
		if(mt_rand(1, 3) === 2){
			$drops[] = VanillaItems::SPIDER_EYE();
		}
        return $drops;
    }

    public function getXpDropAmount(): int{
        return 5 + mt_rand(1, 3);
    }
}