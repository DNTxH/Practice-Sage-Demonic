<?php

namespace vale\sage\demonic\spawner\entity;

use vale\sage\demonic\spawner\SpawnerBase;
use pocketmine\entity\EntitySizeInfo;
use pocketmine\item\VanillaItems;
use pocketmine\player\Player;
use pocketmine\network\mcpe\protocol\types\entity\EntityIds;
class Pig extends SpawnerBase
{
	protected function getInitialSizeInfo() : EntitySizeInfo{
		return new EntitySizeInfo(0.9, 0.9);
	}
	
	public static function getNetworkTypeId() : string{
		return EntityIds::PIG;
	}
	
	public function getName(): string{
        return "Pig";
    }
	
	public function getDrops(): array{
        $drops = [
			VanillaItems::RAW_PORKCHOP()->setCount(mt_rand(1, 3))
        ];
        return $drops;
    }

    public function getXpDropAmount(): int{
        return mt_rand(1, 3);
    }
}