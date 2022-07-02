<?php
    
namespace vale\sage\demonic\entitys\types;

use vale\sage\demonic\Loader;
use pocketmine\entity\Entity;
use pocketmine\entity\object\FallingBlock;
use pocketmine\event\entity\EntityDamageByEntityEvent;
use pocketmine\player\Player;
use pocketmine\scheduler\ClosureTask;

class CustomFallingBlockEntity extends FallingBlock{

    private int $ticks = 0;

    public function canCollideWith(Entity $entity): bool{
        return $entity instanceof Player;
    }

    public function onCollideWithPlayer(Player $player): void{
        $name = $player->getName();
        $attacker = $this->getOwningEntity();
        if ($this->getOwningEntity() !== null && $attacker instanceof Player) {
            if ($name === $attacker->getName()) return;
        }

        $event = new EntityDamageByEntityEvent($this, $player, EntityDamageByEntityEvent::CAUSE_SUFFOCATION, 12);
        $event->call();
        $event->setKnockBack(0);
        if (!$event->isCancelled()) {
            $player->attack($event);
        }
    }

    protected function entityBaseTick(int $tickDiff = 1): bool{
        if ($this->closed) return false;

        if (!$this->isFlaggedForDespawn()) {
            $world = $this->getWorld();
            $position = $this->location->add(-$this->size->getWidth() / 2, $this->size->getHeight(), -$this->size->getWidth() / 2)->floor();

            $this->block->position($world, $position->x, $position->y, $position->z);

            if ($this->ticks++ >= 250 || $this->onGround){
                $this->flagForDespawn();
            }
        }

        return true;
    }
}