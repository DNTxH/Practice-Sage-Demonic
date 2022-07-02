<?php

namespace vale\sage\demonic\spawner;

use vale\sage\demonic\Loader;
use vale\sage\demonic\spawner\SpawnerBase;
use pocketmine\entity\Living;
use pocketmine\event\entity\EntityDeathEvent;
use pocketmine\nbt\tag\IntTag;
use pocketmine\player\Player;
use pocketmine\utils\TextFormat as C;
use pocketmine\item\Item;

class Mobstacker
{	
    public function __construct(public Living $entity){}
	
    public function stack(): void{
        if($this->isStacked()){
            $this->updateNameTag();
            return;
        }
        if(($mob = $this->findNearStack()) === null){
			$this->entity->setStack($this->entity->getStack() + mt_rand(20, 40));
            $mobstack = $this;
        }else{
            $this->entity->flagForDespawn();
            $mobstack = new Mobstacker($mob);
            $count = $mob->getStack();
			$count += mt_rand(20, 40);
			$mob->setStack($count);
        }
        $mobstack->updateNameTag();
    }

    public function isStacked(): bool{
		if(!$this->entity instanceof SpawnerBase) return false;
		return $this->entity->getStack() !== 0;
    }

    public function updateNameTag(): void{
        $this->entity->setNameTagVisible(true);
        $this->entity->setNameTagAlwaysVisible(true);
		$this->entity->setNameTag(C::RESET . C::YELLOW . C::BOLD . $this->entity->getName() . C::RESET . C::YELLOW . " x" . $this->entity->getStack());
    }

    public function findNearStack(int $range = 16): ?Living{
        $entity = $this->entity;
        if($entity->isFlaggedForDespawn() or $entity->isClosed()) return null;
        $boundingBox = $entity->getBoundingBox()->expandedCopy($range, $range, $range);
        foreach($entity->getWorld()->getNearbyEntities($boundingBox) as $nearbyEntity){
            if(!$nearbyEntity instanceof Player and $nearbyEntity instanceof Living){
                if($entity->getPosition()->distance($nearbyEntity->getPosition()) <= $range and $entity->getSaveId() === $nearbyEntity->getSaveId()){
                    $ae = new Mobstacker($nearbyEntity);
                    if($ae->isStacked() && !$this->isStacked()){
						return $nearbyEntity;
					}
                }
            }
        }
        return null;
    }

    public function removeStack(Player $player = null): bool{
        $entity = $this->entity;
        if(!$this->isStacked() or ($c = $this->getStackAmount()) <= 1){
            return false;
        }

        $entity->setStack(--$c);
        $event = new EntityDeathEvent($entity, $drops = $entity->getDrops());
        $event->call();
        $this->updateNameTag();

        $exp = $entity->getXpDropAmount();
        if($exp > 0){
            $entity->getWorld()->dropExperience($entity->getPosition(), $exp);
        }
        if(($chest = Loader::getInstance()->getCollectionManager()->getNearestChest($entity->getPosition())) !== null){
            foreach($chest->addItems($drops) as $drop){
                $entity->getWorld()->dropItem($entity->getPosition(), $drop);
            }
            return true;
        }
        foreach($drops as $drop){
            $entity->getWorld()->dropItem($entity->getPosition(), $drop);
        }
        return true;
    }

    /**
     * @return bool
     */
    public function removeStackV2() : bool {
        $entity = $this->entity;
        $c = $this->getStackAmount();

        $entity->setStack(--$c);
        $event = new EntityDeathEvent($entity, $drops = $entity->getDrops());
        $event->call();
        $this->updateNameTag();

        $exp = $entity->getXpDropAmount();
        if($exp > 0){
            $entity->getWorld()->dropExperience($entity->getPosition(), $exp);
        }
        if(($chest = Loader::getInstance()->getCollectionManager()->getNearestChest($entity->getPosition())) !== null){
            foreach($chest->addItems($drops) as $drop){
                $entity->getWorld()->dropItem($entity->getPosition(), $drop);
            }
            return true;
        }
        foreach($drops as $drop){
            $entity->getWorld()->dropItem($entity->getPosition(), $drop);
        }
        return true;
    }
	
    public function getStackAmount(): int{
		return $this->entity->getStack();
    }
}