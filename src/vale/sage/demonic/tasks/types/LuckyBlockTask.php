<?php
namespace vale\sage\demonic\tasks\types;
use pocketmine\block\BlockLegacyIds;
use pocketmine\block\VanillaBlocks;
use pocketmine\player\Player;
use pocketmine\scheduler\Task;
use pocketmine\world\particle\BlockBreakParticle;
use pocketmine\world\particle\HugeExplodeSeedParticle;
use pocketmine\world\Position;
use pocketmine\world\World;

class LuckyBlockTask extends Task{

	public function __construct(
		public Player $player,
		public Position $position,
		public World $world,
		private int $time = 10,
	){
	}

	public function onRun(): void
	{
		if($this->player === null || !$this->player->isOnline() || $this->player->isClosed()){
			$this->getHandler()->cancel();
			return;
		}
		if(!$this->world->getBlock($this->position->subtract(0, 1, 0))->getId() == BlockLegacyIds::BEDROCK){
			$this->getHandler()->cancel();
			return;
		}
		--$this->time;
		if($this->time <= 10 && $this->time > 1){
			$this->world->setBlock($this->position, VanillaBlocks::GLOWING_OBSIDIAN());
			$this->world->addParticle($this->position->add(0.5, 1.5, 0.5), new BlockBreakParticle(VanillaBlocks::OBSIDIAN()));
			return;
		}
		if($this->time === 1){
			$this->world->setBlock($this->position, VanillaBlocks::AIR());
			$this->world->addParticle($this->position, new HugeExplodeSeedParticle());
			$this->rewardPlayer($this->position, $this->player, $this->world);
			$this->getHandler()->cancel();
		}
	}

	/**
	 * @param Position $position
	 * @param Player $player
	 * @param World $world
	 */
	private function rewardPlayer(Position $position, Player $player, World $world){
		$rand = rand(1,10);
		$player->sendMessage("LOL: $rand");
	}
}