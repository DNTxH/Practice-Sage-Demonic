<?php

namespace vale\sage\demonic\spawner;

use pocketmine\entity\Living;
use pocketmine\entity\Location;
use pocketmine\nbt\tag\CompoundTag;
abstract class SpawnerBase extends Living
{
	CONST STACK = 'stack';
	
	private int $stack = 0;
	
	public function __construct(Location $location, CompoundTag $nbt){
		parent::__construct($location, $nbt);
		if(!$nbt->getTag(self::STACK)){
			$nbt->setTag(self::STACK, 0);
		}
	}
	
	public function getStack(): int{
		return $this->stack;
	}
	
	public function setStack(int $value = 1): void{
		$this->stack = $value;
	}
	
	protected function initEntity(CompoundTag $nbt): void{
		parent::initEntity($nbt);
		$this->stack = $nbt->getInt(self::STACK, 0);
		$nbt->setInt(self::STACK, $this->stack);
	}
	
	public function saveNBT(): CompoundTag{
		$nbt = parent::saveNBT();
		$nbt->setInt(self::STACK, $this->stack);
		return $nbt;
	}
	
	public function getSaveId(): string{
		return (new \ReflectionClass($this))->getShortName();
	}
}