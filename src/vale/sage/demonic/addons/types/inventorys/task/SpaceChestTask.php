<?php
namespace vale\sage\demonic\addons\types\inventorys\task;

use muqsit\invmenu\InvMenu;
use pocketmine\item\ItemFactory;
use pocketmine\item\ItemIds;
use pocketmine\item\VanillaItems;
use pocketmine\player\Player;
use pocketmine\scheduler\CancelTaskException;
use pocketmine\scheduler\Task;
use pocketmine\world\sound\ClickSound;
use pocketmine\world\sound\XpLevelUpSound;
use vale\sage\demonic\addons\types\inventorys\SpaceChestInventory;
use vale\sage\demonic\Loader;
use vale\sage\demonic\rewards\redeemable\RedeemableAPI;

class SpaceChestTask extends Task
{

    /** @var int */
	private int $duration = 8;

    /** @var int */
	private int $showcase = 10;

	/** @var array|int[] $INSIDE_GRID */
	public static array $INSIDE_GRID = [
		10, 11, 12, 13, 14, 15, 16, 19, 20, 21, 22, 23, 24, 25, 28, 29, 30, 31, 32, 33, 34, 37, 38, 39, 40, 41, 42, 43
	];

    /** @var bool */
	public $stop = false;

    /**
     * @param InvMenu $menu
     * @param Player $player
     * @param int $rarity
     * @param array $slots
     */
	public function __construct(
		private InvMenu $menu,
		private Player  $player,
		private array $slots,
	)
	{
	}

	public function onRun(): void
	{
		if(isset(SpaceChestInventory::$eliteChest[$this->player->getName()])){
			unset(SpaceChestInventory::$eliteChest[$this->player->getName()]);
		}
		$menu = $this->menu;
		if ($this->player === null || !$this->player->isOnline() || $this->player->isClosed()) {
			$this->getHandler()->cancel();
			return;
		}
		$items = RedeemableAPI::getLootBoxRewards(1);
		if ($this->showcase >= 0 && $this->duration === 8) {
			foreach (self::$INSIDE_GRID as $grid) {
				if (!in_array($grid, $this->slots)) $menu->getInventory()->setItem($grid, $items[array_rand($items)]);
				$this->player->getLocation()->getWorld()->addSound($this->player->getLocation(),new ClickSound());
			}
			--$this->showcase;
		}
		if ($this->showcase === 0) {
			foreach (self::$INSIDE_GRID as $grid) {
				if (!in_array($grid, $this->slots)) $this->menu->getInventory()->setItem($grid, ItemFactory::getInstance()->get(ItemIds::STAINED_GLASS, 15, 1)->setCustomName("§r§8' '"));
			}
			$this->duration--;
		}
		if ($this->duration === 3) {
			$this->slots = [];
			unset(SpaceChestInventory::$underTask[$this->player->getName()]);
			$this->getHandler()->cancel();
		}
	}
}