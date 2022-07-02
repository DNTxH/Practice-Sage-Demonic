<?php

namespace vale\sage\demonic\spawner\entity;

use vale\sage\demonic\spawner\SpawnerBase;
use pocketmine\entity\EntitySizeInfo;
use pocketmine\item\VanillaItems;
use pocketmine\player\Player;
use pocketmine\network\mcpe\protocol\types\entity\EntityIds;
class Zombie extends SpawnerBase
{
	protected function getInitialSizeInfo() : EntitySizeInfo{
		return new EntitySizeInfo(1.95, 0.6);
	}
	
	public static function getNetworkTypeId() : string{
		return EntityIds::ZOMBIE;
	}
	
	public function getName(): string{
        return "Zombie";
    }
	
	public function getDrops(): array{
        $drops = [
			VanillaItems::ROTTEN_FLESH()->setCount(mt_rand(0, 2))
        ];
        if (mt_rand(0, 199) < 5) {
            switch (mt_rand(0, 2)) {
                case 0:
                    $drops[] = VanillaItems::IRON_INGOT();
				break;
                case 1:
                    $drops[] = VanillaItems::CARROT();
				break;
                case 2:
                    $drops[] = VanillaItems::POTATO();
				break;
            }
        }
        return $drops;
    }

    public function getXpDropAmount(): int{
        return 5 + mt_rand(1, 3);
    }
}