<?php

namespace vale\sage\demonic\spawner\entity;

use vale\sage\demonic\spawner\SpawnerBase;
use pocketmine\entity\EntitySizeInfo;
use pocketmine\item\ItemFactory;
use pocketmine\item\ItemIds;
use pocketmine\item\VanillaItems;
use pocketmine\nbt\tag\CompoundTag;
use pocketmine\player\Player;
use pocketmine\network\mcpe\protocol\types\entity\EntityIds;
class IronGolem extends SpawnerBase
{
	protected function getInitialSizeInfo() : EntitySizeInfo{
		return new EntitySizeInfo(2.7, 1.4);
	}
	
	public static function getNetworkTypeId() : string{
		return EntityIds::IRON_GOLEM;
	}
	
	public function getName(): string{
        return "Iron Golem";
    }
	
	public function initEntity(CompoundTag $nbt): void{
        $this->setMaxHealth(100);
        $this->setHealth(100);
        parent::initEntity($nbt);
    }
	
	public function getDrops(): array{
        $cause = $this->lastDamageCause;
		$iron = VanillaItems::IRON_INGOT()->setCount(mt_rand(1, 2));
        $rose = ItemFactory::getInstance()->get(ItemIds::RED_FLOWER);
        if(mt_rand(0, 5) === 0){
            return [$iron, $rose];
        }
        return [$iron];
    }

    public function getXpDropAmount(): int{
        return mt_rand(1, 3);
    }
}