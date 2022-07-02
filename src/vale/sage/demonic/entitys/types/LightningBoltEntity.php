<?php
namespace vale\sage\demonic\entitys\types;
use vale\sage\demonic\Loader;
use pocketmine\entity\Entity;
use pocketmine\entity\EntitySizeInfo;
use pocketmine\entity\Living;
use pocketmine\entity\Location;
use pocketmine\event\entity\EntityDamageByEntityEvent;
use pocketmine\event\entity\EntityDamageEvent;
use pocketmine\nbt\tag\CompoundTag;
use pocketmine\network\mcpe\protocol\types\entity\EntityIds;
use pocketmine\scheduler\CancelTaskException;
use pocketmine\scheduler\ClosureTask;

class LightningBoltEntity extends Entity{

    protected int $age = 0;

    public function __construct(Location $location, private float $damage = 5, ?CompoundTag $nbt = null, public int $freeze = 0){
        parent::__construct($location, $nbt);
    }

    public static function getNetworkTypeId(): string{
        return EntityIds::LIGHTNING_BOLT;
    }

    public function getInitialSizeInfo(): EntitySizeInfo{
        return new EntitySizeInfo(1.8, 0.3);
    }

    public function entityBaseTick(int $tickDiff = 1): bool{
        if ($this->closed) return false;

        foreach ($this->getWorld()->getNearbyEntities($this->getBoundingBox()->expandedCopy(6, 6, 6), $this) as $entity) {
            if ($entity instanceof Living && $entity->isAlive() && $this->getOwningEntity() !== $entity) {
                $event = new EntityDamageByEntityEvent($this, $entity, EntityDamageEvent::CAUSE_MAGIC, $this->damage);
                $event->call();
                $event->setKnockBack(0);
                if (!$event->isCancelled()) {
                    $entity->attack($event);
                }
                if ($this->freeze > 0) {
                    $entity->setImmobile(true);

                    Loader::getInstance()->getScheduler()->scheduleDelayedRepeatingTask(new ClosureTask(function () use ($entity): void {
                        $entity->setImmobile(false);
                        throw new CancelTaskException();
                    }), 20 * $this->freeze, 20 * $this->freeze);
                }
            }
        }

        if (($this->age += $tickDiff) > 20) $this->flagForDespawn();
        return parent::entityBaseTick($tickDiff);
    }
}