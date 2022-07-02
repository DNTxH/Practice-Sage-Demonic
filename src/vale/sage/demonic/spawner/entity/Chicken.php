<?php

namespace vale\sage\demonic\spawner\entity;

use vale\sage\demonic\spawner\SpawnerBase;
use pocketmine\entity\EntitySizeInfo;
use pocketmine\item\VanillaItems;
use pocketmine\player\Player;
use pocketmine\network\mcpe\protocol\types\entity\EntityIds;
class Chicken extends SpawnerBase
{
	protected function getInitialSizeInfo() : EntitySizeInfo{
		return new EntitySizeInfo(0.6, 0.6);
	}
	
	public static function getNetworkTypeId() : string{
		return EntityIds::CHICKEN;
	}
	
	public function getName(): string{
        return "Chicken";
    }
	
	public function getDrops(): array{
        $drops = [
			VanillaItems::FEATHER()->setCount(mt_rand(0, 2)),
			VanillaItems::RAW_CHICKEN()
        ];
        return $drops;
    }

    public function getXpDropAmount(): int{
        return mt_rand(1, 3);
    }
}