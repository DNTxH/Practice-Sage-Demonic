<?php

namespace vale\sage\demonic\spawner\entity;

use vale\sage\demonic\spawner\SpawnerBase;
use pocketmine\entity\EntitySizeInfo;
use pocketmine\item\ItemFactory;
use pocketmine\item\ItemIds;
use pocketmine\item\VanillaItems;
use pocketmine\player\Player;
use pocketmine\network\mcpe\protocol\types\entity\EntityIds;
class Sheep extends SpawnerBase
{
	protected function getInitialSizeInfo() : EntitySizeInfo{
		return new EntitySizeInfo(1.3, 0.9);
	}
	
	public static function getNetworkTypeId() : string{
		return EntityIds::SHEEP;
	}
	
	public function getName(): string{
        return "Sheep";
    }
	
	public function getDrops(): array{
        $drops = [
			ItemFactory::getInstance()->get(ItemIds::WOOL, 0, 1),
			VanillaItems::RAW_MUTTON()->setCount(mt_rand(1, 2)),
        ];
        return $drops;
    }

    public function getXpDropAmount(): int{
        return mt_rand(1, 3);
    }
}