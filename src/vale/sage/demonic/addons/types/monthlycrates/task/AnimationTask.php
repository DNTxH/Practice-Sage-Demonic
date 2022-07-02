<?php
namespace vale\sage\demonic\addons\types\monthlycrates\task;

use pocketmine\block\Chest;
use pocketmine\block\tile\Tile;
use pocketmine\block\VanillaBlocks;
use pocketmine\entity\Location;
use pocketmine\item\VanillaItems;
use pocketmine\player\Player;
use pocketmine\scheduler\Task;
use pocketmine\world\particle\CriticalParticle;
use pocketmine\world\particle\FlameParticle;
use pocketmine\world\particle\HugeExplodeSeedParticle;
use pocketmine\world\Position;
use pocketmine\world\sound\XpLevelUpSound;
use vale\sage\demonic\addons\types\monthlycrates\entity\EnderChestEntity;
use vale\sage\demonic\enchants\EnchantManager;
use vale\sage\demonic\rewards\Rewards;

class AnimationTask extends Task
{

    /** @var bool */
	public bool $enabled;

    /** @var Player */
	public Player $player;

    /** @var Location */
	public Location $location;

    /** @var EnderChestEntity */
	public EnderChestEntity $enderChestEntity;

    /** @var array */
	public array $entites = [];

    /** @var int $ticks */
    private int $ticks = 0;

    /** @var int $swap */
    private int $swap = 0;

    /** @var int $amount */
    public int $amount = 4;

	/**
	 * @param Player $player
	 * @param EnderChestEntity $enderChestEntity
	 * @param Location $location
	 * @param bool $enabled
	 */
	public function __construct(Player $player, EnderChestEntity $enderChestEntity, Location $location, bool $enabled,)
	{
		$this->player = $player;
		$this->location = $location;
		$this->enderChestEntity = $enderChestEntity;
		$this->enderChestEntity->spawnToAll();
		$this->enabled = $enabled;
	}

	public function onRun(): void
	{
		if ($this->enabled === false) {
			$this->getHandler()->cancel();
			return;
		}

		if (!$this->player->isOnline()) {
			$this->enderChestEntity->flagForDespawn();
			$this->enabled = false;
			$this->getHandler()->cancel();
			return;
		}
		$items = [
			VanillaItems::DIAMOND_SWORD()->setCustomName("§r§4§lDemon Sword"),
			VanillaItems::DIAMOND_AXE()->setCustomName("§r§6§lTimber Axe"),
			VanillaItems::GOLDEN_SHOVEL()->setCustomName("§r§e§lGolden Weenier"),
			VanillaItems::ENDER_PEARL()->setCustomName("§r§a§lPearl Reset"),
			VanillaItems::BUCKET()->setCustomName("§r§c§lKFC")
		];
		$this->ticks++;
		$this->swap++;
		if ($this->ticks === 4) {
			$ratio = 1;
			for ($y = 0; $y < 10; $y += 0.2) {
				$x = $ratio * cos($y);
				$z = $ratio * sin($y);
				$this->location->getWorld()->addParticle($this->location->add($x, $y, $z), new FlameParticle());
			}
		}

		if ($this->ticks >= 5) {
			$vector = EnchantManager::getRandomVector()->multiply(4);
			$this->location->getWorld()->addParticle($this->location->add($vector->x, $vector->y, $vector->z), new CriticalParticle());
			$this->location->add($vector->x, $vector->y, $vector->z);
		}

		if ($this->ticks >= 7) {
			$ratio = 1;
			for ($y = 0; $y < 10; $y += 0.2) {
				$x = $ratio * cos($y);
				$z = $ratio * sin($y);
				$this->location->getWorld()->addParticle($this->location->add($x, $y, $z), new FlameParticle());
			}
		}

		if ($this->ticks === 8) {
			EnchantManager::Lightning($this->enderChestEntity->getLocation());
			$this->enderChestEntity->setScale(5);
		}

		if ($this->ticks >= 10) {
			$this->enderChestEntity->setScale(1.6);
			$this->enderChestEntity->getLocation()->y += 0.3;
		}
		if ($this->ticks === 13) {
			$this->location->getWorld()->addParticle($this->location, new HugeExplodeSeedParticle());
			$this->location->getWorld()->setBlock($this->location, VanillaBlocks::CHEST());
			$block = $this->location->getWorld()->getTile($this->location->asPosition());
			$this->enderChestEntity->flagForDespawn();
			if ($block instanceof \pocketmine\block\tile\Chest) {
				$inv = $block->getInventory();
				$inv->setItem(0, Rewards::get(Rewards::TEST, 10));
				$inv->setItem(1, Rewards::get(Rewards::LOOTBOX, rand(1, 2)));
			}
			$this->enabled = false;
			$this->player->getWorld()->addSound($this->player->getLocation(), new XpLevelUpSound(100));
		}
	}
}