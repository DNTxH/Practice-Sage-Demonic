<?php

namespace vale\sage\demonic\spawner\entity;

use vale\sage\demonic\spawner\SpawnerBase;
use pocketmine\entity\EntitySizeInfo;
use pocketmine\item\VanillaItems;
use pocketmine\player\Player;
use pocketmine\network\mcpe\protocol\types\entity\EntityIds;
class Wolf extends SpawnerBase
{
	protected function getInitialSizeInfo() : EntitySizeInfo{
		return new EntitySizeInfo(0.85, 0.6);
	}
	
	public static function getNetworkTypeId() : string{
		return EntityIds::WOLF;
	}
	
	public function getName(): string{
        return "Wolf";
    }
	
	public function getDrops(): array{
        return [
			VanillaItems::BONE()->setCount(mt_rand(0, 3))
        ];
    }

    public function getXpDropAmount(): int{
        return mt_rand(1, 3);
    }
}