<?php

namespace vale\sage\demonic\spawner\block;

use vale\sage\demonic\Loader;
use vale\sage\demonic\spawner\SpawnerUtils;
use vale\sage\demonic\spawner\tile\MobSpawner;
use pocketmine\entity\Entity;
use pocketmine\entity\Human;
use pocketmine\entity\Location;
use pocketmine\block\Block;
use pocketmine\block\BlockLegacyIds;
use pocketmine\event\block\BlockPlaceEvent;
use pocketmine\item\Item;
use pocketmine\math\Vector3;
use pocketmine\player\Player;
use pocketmine\utils\TextFormat as C;
use pocketmine\scheduler\ClosureTask;
use pocketmine\world\BlockTransaction;
class MonsterSpawner extends \pocketmine\block\MonsterSpawner 
{
	public function place(BlockTransaction $tx, Item $item, Block $blockReplace, Block $blockClicked, int $face, Vector3 $clickVector, ?Player $player = null): bool{
        $parent = parent::place($tx, $item, $blockReplace, $blockClicked, $face, $clickVector, $player);
		Loader::getInstance()->getScheduler()->scheduleDelayedTask(new ClosureTask(function() use ($item): void {
			$nbt = $item->getNamedTag();
			if($nbt->getTag(MobSpawner::ENTITY)){
				$entity = $nbt->getString(MobSpawner::ENTITY);
				$tile = $this->position->getWorld()->getTile($this->position);
				if($tile instanceof MobSpawner){
					$tile->setEntity($entity);
				}
			}
		}), 1);
        return $parent;
    }

    public function getDrops(Item $item): array{
        return [];
    }

    public function getSilkTouchDrops(Item $item): array{
        return [];
    }
	
	public function onScheduledUpdate() : void{
		$tile = $this->position->getWorld()->getTile($this->position);
		if(!$tile instanceof MobSpawner) return;
        if($tile->closed === true){
            return;
        }
        if($this->canUpdate($tile)){
            if($tile->getDelay() <= 0){
                $success = 0;
                for($i = 0; $i < 16; $i++){
                    if($success > 0){
                        $tile->setDelay($tile->getBaseDelay());
						$this->position->getWorld()->scheduleDelayedBlockUpdate($this->position, 1);
                        return;
                    }
                    $pos = $tile->getPosition()->add(mt_rand() / mt_getrandmax() * $tile->getSpawnRange(), mt_rand(-1, 1), mt_rand() / mt_getrandmax() * $tile->getSpawnRange());
					$target = $this->getPosition()->getWorld()->getBlock($pos);
                    if($target->getId() === BlockLegacyIds::AIR){
                        $success++;
						$nbt = Loader::getInstance()->getSpawnerManager()->createBaseNBT($target->getPosition()->add(0.5, 0, 0.5), null, lcg_value() * 360);
						$entity = Loader::getInstance()->getSpawnerManager()->createEntity($tile->getEntity(), Location::fromObject($target->getPosition(), null), $nbt);
						if($entity instanceof Entity){
							$entity->spawnToAll();
						}
                    }
                }
                if($success > 0){
                    $tile->setDelay($tile->getBaseDelay());
                }
            }else{
                $tile->setDelay($tile->getDelay() - 1);
            }
        }
		$this->position->getWorld()->scheduleDelayedBlockUpdate($this->position, 1);
	}
	
	public function canUpdate(MobSpawner $tile): bool{
        if(!$tile->getPosition()->getWorld()->isChunkLoaded($tile->getPosition()->getX() >> 4, $tile->getPosition()->getZ() >> 4)){
            return false;
        }
        if($tile->getEntity() === "unknown"){
            return false;
        }
        if($tile->getPosition()->getWorld()->getNearestEntity($tile->getPosition(), 25, Human::class) instanceof Player) {
            return true;
        }
        return false;
    }
}