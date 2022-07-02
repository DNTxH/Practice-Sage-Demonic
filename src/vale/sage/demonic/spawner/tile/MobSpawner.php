<?php

namespace vale\sage\demonic\spawner\tile;

use vale\sage\demonic\spawner\SpawnerUtils;
use pocketmine\block\tile\Spawnable;
use pocketmine\nbt\tag\CompoundTag;
class MobSpawner extends Spawnable
{
    public const LOAD_RANGE = "LoadRange";
    public const ENTITY = "Entity";
    public const SPAWN_RANGE = "SpawnRange";
    public const BASE_DELAY = "BaseDelay";
    public const DELAY = "Delay";
    public const COUNT = "Count";

	private int $loadRange = 8;
	private string $entity = "unknown";
	private int $spawnRange = 4;
	private int $delay = 20;
	private int $baseDelay = 20;
	private int $count = 1;
	
	public function readSaveData(CompoundTag $nbt): void{
		$this->entity = $nbt->getString(self::ENTITY, "unknown");
		$this->loadRange = $nbt->getInt(self::LOAD_RANGE, 8);
		$this->spawnRange = $nbt->getInt(self::SPAWN_RANGE, 4);
		$this->count = $nbt->getInt(self::COUNT, 1);
		$this->baseDelay = $nbt->getInt(self::BASE_DELAY, 20);
		$this->delay = $nbt->getInt(self::DELAY, 20);
		$this->position->getWorld()->scheduleDelayedBlockUpdate($this->position, 1);
	}
	
	public function getDelay(): int{
        return $this->delay;
    }

    public function setDelay(int $value): void{
		$this->delay = $value;
    }
	
	public function getBaseDelay(): int{
        $count = $this->getCount();
        $baseDelay = 800 / $count;
        $this->setBaseDelay($baseDelay);
        return $baseDelay;
    }
	
	public function setBaseDelay(int $value): void{
		$this->baseDelay = $value;
    }
	
	public function getSpawnRange(): int{
        return $this->spawnRange;
    }
	
	public function setSpawnRange(int $value): void{
        $this->spawnRange = $value;
    }
	
	public function getCount(): int{
        return $this->count;
    }

    public function setCount(int $value): void{
       $this->count = $value;
    }

    public function getName(): string{
		return SpawnerUtils::getEntityName($this->getEntity());
    }

    public function getLoadRange(): int{
        return $this->loadRange;
    }

    public function setLoadRange(int $range): void{
        $this->loadRange = $range;
    }
	
    public function getEntity(): string{
        return $this->entity;
    }

    public function setEntity(string $id): void{
        $this->entity = $id;
		$this->setDirty();
    }
	
    public function addAdditionalSpawnData(CompoundTag $nbt): void{
		
    }

    protected function writeSaveData(CompoundTag $nbt): void{
        $nbt->setString(self::ENTITY, $this->entity);
		$nbt->setInt(self::LOAD_RANGE, $this->loadRange);
        $nbt->setInt(self::SPAWN_RANGE, $this->spawnRange);
        $nbt->setInt(self::BASE_DELAY, $this->baseDelay);
        $nbt->setInt(self::DELAY, $this->delay);
        $nbt->setInt(self::COUNT, $this->count);
    }
}