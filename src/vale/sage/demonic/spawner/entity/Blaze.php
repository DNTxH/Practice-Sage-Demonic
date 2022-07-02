<?php

namespace vale\sage\demonic\spawner\entity;

use vale\sage\demonic\spawner\SpawnerBase;
use pocketmine\entity\EntitySizeInfo;
use pocketmine\item\VanillaItems;
use pocketmine\player\Player;
use pocketmine\network\mcpe\protocol\types\entity\EntityIds;
class Blaze extends SpawnerBase
{
	protected function getInitialSizeInfo() : EntitySizeInfo{
		return new EntitySizeInfo(1.8, 0.6);
	}
	
	public static function getNetworkTypeId() : string{
		return EntityIds::BLAZE;
	}
	
	public function getName(): string{
        return "Blaze";
    }
	
	public function getDrops(): array{
        return [VanillaItems::BLAZE_ROD()->setCount(mt_rand(0, 1))];
    }

    public function getXpDropAmount(): int{
        return 10;
    }
}