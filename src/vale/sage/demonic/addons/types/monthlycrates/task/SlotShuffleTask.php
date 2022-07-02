<?php
namespace vale\sage\demonic\addons\types\monthlycrates\task;
use muqsit\invmenu\InvMenu;
use pocketmine\item\ItemFactory;
use pocketmine\item\ItemIds;
use pocketmine\item\VanillaItems;
use pocketmine\player\Player;
use pocketmine\scheduler\CancelTaskException;
use pocketmine\scheduler\Task;
use vale\sage\demonic\Loader;
use vale\sage\demonic\rewards\redeemable\RedeemableAPI;
use vale\sage\demonic\Utils;

class SlotShuffleTask extends Task
{
	/** @var int $time */
	public int $time = 12;

    /**
     * @param Player $player
     * @param int $slot
     * @param int $clicked
     * @param InvMenu $menu
     * @param int $rarity
     */
	public function __construct(
		private Player $player,
		private int $slot,
		private int $clicked,
		private InvMenu $menu,
		private int $rarity,
	)
	{
		if ($this->clicked >= 0) {
			$outside = range(0, 53);
			foreach ($outside as $grid) {
				if (!in_array($grid, [30, 31, 32, 23, 22, 21, 14, 13, 12, 49])) {
					$this->menu->getInventory()->setItem($grid, ItemFactory::getInstance()->get(ItemIds::STAINED_GLASS_PANE, 15, 1)->setCustomName("§r§8 ' "));
				}
			}
		}
	}

	public function onRun(): void
	{

		$items = RedeemableAPI::getMCRewardsById($this->rarity);
		if ($this->player === null || !$this->player->isOnline() || $this->player->isClosed()) {
			$this->getHandler()->cancel();
			return;
		}
		if ($this->time > 0) {
			$this->menu->getInventory()->setItem($this->slot, $items[array_rand($items)]);
			Loader::playSound($this->player, "block.click",3,1);
		}
		Utils::animateBySlot($this->slot, $this->menu, $this->player);
		if ($this->clicked === 9 && $this->time <= 0) {
			Loader::getInstance()->getScheduler()->scheduleRepeatingTask(new MonthlyCrateTickTask($this->player, $this->clicked, $this->menu,$this->rarity), 20);
		}

		if($this->time <= 0){
			$this->getHandler()->cancel();
		}
		--$this->time;
	}
}
