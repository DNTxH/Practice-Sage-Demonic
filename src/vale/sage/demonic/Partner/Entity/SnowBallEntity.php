<?php

namespace vale\sage\demonic\Partner\Entity;

use Partner\Task\TeleportPlayer;
use pocketmine\entity\Entity;
use pocketmine\entity\projectile\Snowball;
use pocketmine\math\RayTraceResult;
use pocketmine\player\Player;
use vale\sage\demonic\Loader;

class SnowBallEntity extends Snowball
{
    protected function onHitEntity(Entity $entityHit, RayTraceResult $hitResult) : void{
        $thrower = $this->getOwningEntity();
        if($thrower instanceof Player && $entityHit instanceof Player) {
            foreach ($thrower->getWorld()->getNearbyEntities($thrower->getBoundingBox()->expandedCopy(7, 7, 7)) as $entity) {
                if ($entity === $entityHit) {
                    $thrower_pos = $thrower->getPosition();
                    $entity_pos = $entityHit->getPosition();
                    $thrower->sendMessage("§eYou have successfully used §dTeleportation Ball");
                    $thrower->sendMessage("§eNow cooldown for §d4 minutes");
                    Loader::getInstance()->getScheduler()->scheduleDelayedTask(new TeleportPlayer($thrower, $entity_pos, "§aSuccessfully teleport to " . $entityHit->getName() . "positions!"), 20);
                    Loader::getInstance()->getScheduler()->scheduleDelayedTask(new TeleportPlayer($entityHit, $thrower_pos, "§aSuccessfully teleport to " . $thrower->getName() . "positions!"), 20);
                    $thrower->sendMessage("§r§7You will be teleport to§l§a " . $entityHit->getName() . " §r§7Positions after 5 seconds!");
                    $entityHit->sendMessage("§r§7".$thrower->getName() . " §r§7use Teleportation Ball!");
                    $entityHit->sendMessage("§r§7You will be teleport to§l§a" . $thrower->getName() . "§r§7Positions after 5 seconds!");
                    $this->flagForDespawn();
                    return;
                }
            }
            $thrower->sendMessage("§r§4 The player you hit is out of 7 radius!");
            $this->flagForDespawn();
        }
    }
}