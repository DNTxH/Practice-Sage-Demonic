<?php

namespace vale\sage\demonic\spawner\entity;

use vale\sage\demonic\spawner\SpawnerBase;
use pocketmine\entity\EntitySizeInfo;
use pocketmine\item\VanillaItems;
use pocketmine\player\Player;
use pocketmine\network\mcpe\protocol\types\entity\EntityIds;
class Skeleton extends SpawnerBase
{
	protected function getInitialSizeInfo() : EntitySizeInfo{
		return new EntitySizeInfo(1.99, 0.6);
	}
	
	public static function getNetworkTypeId() : string{
		return EntityIds::SKELETON;
	}
	
	public function getName(): string{
        return "Skeleton";
    }
	
	public function getDrops(): array{
        return [
			VanillaItems::ARROW()->setCount(mt_rand(0, 2)),
			VanillaItems::BONE()->setCount(mt_rand(0, 2))
        ];
    }

    public function getXpDropAmount(): int{
        return 5 + mt_rand(1, 3);
    }
}