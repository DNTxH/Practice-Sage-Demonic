<?php

namespace vale\sage\demonic\spawner\entity;

use vale\sage\demonic\spawner\SpawnerBase;
use pocketmine\entity\EntitySizeInfo;
use pocketmine\item\VanillaItems;
use pocketmine\player\Player;
use pocketmine\network\mcpe\protocol\types\entity\EntityIds;
class Enderman extends SpawnerBase
{
	protected function getInitialSizeInfo() : EntitySizeInfo{
		return new EntitySizeInfo(1.8, 0.9);
	}
	
	public static function getNetworkTypeId() : string{
		return EntityIds::ENDERMAN;
	}
	
	public function getName(): string{
        return "Enderman";
    }
	
	public function getDrops(): array{
        return [VanillaItems::ENDER_PEARL()->setCount(mt_rand(0, 1))];
    }

    public function getXpDropAmount(): int{
        return 5 + mt_rand(1, 3);
    }
}