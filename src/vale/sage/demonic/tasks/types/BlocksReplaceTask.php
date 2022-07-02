<?php
namespace vale\sage\demonic\tasks;
use pocketmine\block\Block;
use pocketmine\block\Opaque;
use pocketmine\block\VanillaBlocks;
use pocketmine\math\Vector3;
use pocketmine\network\mcpe\protocol\BlockEventPacket;
use pocketmine\network\mcpe\protocol\LevelEventPacket;
use pocketmine\network\mcpe\protocol\types\LevelEvent;
use pocketmine\player\Player;
use pocketmine\scheduler\Task;


/**
 * Class BlocksReplaceTask
 * @package vale\sage\demonic\tasks
 * @author Jibix
 * @date 07.01.2022 - 00:16
 * @project Genesis
 */
class BlocksReplaceTask extends Task{

    /** @var Block[] */
    public array $blocks = [];

    public function __construct(public Player $position, public int $radius = 10){
        $axisAligned = $position->getBoundingBox();
        $axisAligned = $axisAligned->expandedCopy($radius, $radius, $radius);;

        $world = $position->getWorld();
        for ($x = $axisAligned->minX; $x <= $axisAligned->maxX; ++$x) {
            for ($y = $axisAligned->minY; $y <= $axisAligned->maxY; ++$y) {
                for ($z = $axisAligned->minZ; $z <= $axisAligned->maxZ; ++$z) {
                    $vector = new Vector3($x, $y, $z);
                    $block = $world->getBlock($vector, false, false);
                    if ($block instanceof Opaque) {
                        $this->blocks[] = $block;
                        $world->setBlock($vector, VanillaBlocks::SNOW());
                        #Todo: Add an air check for the block on top and under the block
                    }
                }
            }
        }
        foreach ($position->getWorld()->getEntities() as $entity) {
            if ($position->getPosition()->distance($entity->getPosition()) <= $this->radius) {
                if ($entity instanceof Player && $entity->getName() !== $position->getName()) {
                    $entity->setImmobile(true);
                    $pk = new LevelEventPacket();
                    $pk->eventId = LevelEvent::START_RAIN;
                    $pk->eventData = 10000;
                    $pk->position = $position->getPosition();
                    $entity->getNetworkSession()->sendDataPacket($pk);
                }
            }
        }
    }

    public function onRun(): void{
        foreach ($this->blocks as $block) {
            $vector = $block->getPosition();
            $block->getPosition()->getWorld()->setBlock($vector, $block, false);
        }
        unset($this->blocks);
        $position = $this->position->getPosition();
        foreach ($position->getWorld()->getEntities() as $entity) {
            if ($position->distance($entity->getPosition()) <= $this->radius) {
                $entity->setImmobile(false);
                if ($entity instanceof Player) {
                    $pk = new LevelEventPacket();
                    $pk->eventId = LevelEvent::STOP_RAIN;
                    $pk->eventData = 0;
                    $pk->position = $position;
                    $entity->getNetworkSession()->sendDataPacket($pk);
                }
            }
        }
        $this->getHandler()->cancel();
    }
}