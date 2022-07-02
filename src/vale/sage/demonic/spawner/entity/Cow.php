<?php

namespace vale\sage\demonic\spawner\entity;

use vale\sage\demonic\spawner\SpawnerBase;
use pocketmine\entity\EntitySizeInfo;
use pocketmine\item\VanillaItems;
use pocketmine\player\Player;
use pocketmine\network\mcpe\protocol\types\entity\EntityIds;
class Cow extends SpawnerBase
{
	protected function getInitialSizeInfo() : EntitySizeInfo{
		return new EntitySizeInfo(1.3, 0.9);
	}
	
	public static function getNetworkTypeId() : string{
		return EntityIds::COW;
	}
	
	public function getName(): string{
        return "Cow";
    }
	
	public function getDrops(): array{
        $drops = [
			VanillaItems::RAW_BEEF()->setCount(mt_rand(1, 3)),
			VanillaItems::LEATHER()->setCount(mt_rand(0, 2))
        ];
        return $drops;
    }

    public function getXpDropAmount(): int{
        return mt_rand(1, 3);
    }
}