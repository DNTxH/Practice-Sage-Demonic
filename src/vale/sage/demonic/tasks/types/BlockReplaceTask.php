<?php
namespace vale\sage\demonic\tasks\types;
use pocketmine\block\VanillaBlocks;
use pocketmine\entity\Entity;
use pocketmine\item\ItemIds;
use pocketmine\player\Player;
use pocketmine\scheduler\Task;;
use pocketmine\world\Position;

class BlockReplaceTask extends Task{

	public int $duration = 2;

	public function __construct(
		private Position $block,
		private ?int $blockid,
		private Player $player,
	    private Entity $entity,
	){
	}

	public function onRun(): void
	{
		if ($this->player === null || !$this->player->isOnline() || $this->player->isClosed()) {
			$x = $this->block->getX();
			$y = $this->block->getY();
			$z = $this->block->getZ();
			if ($this->player->getWorld()->getBlockAt($x, $y, $z)->getId() === $this->blockid) {
				$this->player->getWorld()->setBlock(new Position($x, $y, $z, $this->player->getWorld()), VanillaBlocks::AIR());
			}
			$this->getHandler()->cancel();
			return;
		}
		--$this->duration;
		$this->entity->teleport($this->block);
	 if($this->duration <= 0){
		 $x = $this->block->getX();
		 $y = $this->block->getY();
		 $z = $this->block->getZ();
		if($this->player->getWorld()->getBlockAt($x,$y,$z)->getId() !== ItemIds::AIR) {
			if ($this->player->getWorld()->getBlockAt($x, $y, $z)->getId() === $this->blockid) {
				$this->player->getWorld()->setBlock(new Position($x, $y, $z, $this->player->getWorld()), VanillaBlocks::AIR());
			}
		}
		$this->getHandler()->cancel();
	 }
	}
}