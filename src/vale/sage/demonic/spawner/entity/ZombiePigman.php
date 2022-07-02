<?php

namespace vale\sage\demonic\spawner\entity;

use vale\sage\demonic\spawner\SpawnerBase;
use pocketmine\entity\EntitySizeInfo;
use pocketmine\item\VanillaItems;
use pocketmine\player\Player;
use pocketmine\network\mcpe\protocol\types\entity\EntityIds;
class ZombiePigman extends SpawnerBase
{
	protected function getInitialSizeInfo() : EntitySizeInfo{
		return new EntitySizeInfo(1.95, 0.6);
	}
	
	public static function getNetworkTypeId() : string{
		return EntityIds::ZOMBIE_PIGMAN;
	}
	
	public function getName(): string{
        return "Zombie Pigman";
    }
	
	public function getDrops(): array{
		$drops = [
			VanillaItems::GOLD_NUGGET()->setCount(mt_rand(0, 1)),
			VanillaItems::ROTTEN_FLESH()->setCount(mt_rand(0, 1))
		];
		if(mt_rand(1, 200) <= 7){
			$drops[] = VanillaItems::GOLD_INGOT();
		}
        return $drops;
    }

    public function getXpDropAmount(): int{
        return 5 + mt_rand(1, 3);
    }
}