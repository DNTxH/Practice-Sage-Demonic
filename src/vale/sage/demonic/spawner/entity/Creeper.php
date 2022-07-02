<?php

namespace vale\sage\demonic\spawner\entity;

use vale\sage\demonic\spawner\SpawnerBase;
use pocketmine\entity\EntitySizeInfo;
use pocketmine\item\VanillaItems;
use pocketmine\player\Player;
use pocketmine\network\mcpe\protocol\types\entity\EntityIds;
class Creeper extends SpawnerBase
{
	protected function getInitialSizeInfo() : EntitySizeInfo{
		return new EntitySizeInfo(1.7, 0.6);
	}
	
	public static function getNetworkTypeId() : string{
		return EntityIds::CREEPER;
	}
	
	public function getName(): string{
        return "Creeper";
    }
	
	public function getDrops(): array{
        if(mt_rand(1, 10) < 3){
            return [VanillaItems::GUNPOWDER()];
        }
        return [];
    }

    public function getXpDropAmount(): int{
        return 5 + mt_rand(1, 3);
    }
}