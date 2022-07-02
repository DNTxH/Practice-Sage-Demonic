<?php
namespace vale\sage\demonic\addons\types\monthlycrates\task;
use BlockHorizons\Fireworks\item\Fireworks;
use muqsit\invmenu\InvMenu;
use pocketmine\entity\Location;
use pocketmine\item\ItemFactory;
use pocketmine\item\ItemIds;
use pocketmine\item\VanillaItems;
use pocketmine\player\Player;
use pocketmine\scheduler\Task;
use pocketmine\Server;
use pocketmine\world\sound\AnvilUseSound;
use pocketmine\world\sound\XpLevelUpSound;
use vale\sage\demonic\Loader;
use vale\sage\demonic\Utils;

class MonthlyCrateTickTask extends Task
{
    /** @var array */
	public static array $recieve = [];

    /** @var Player */
	private Player $player;

    /** @var int */
	private int $clicked;

    /** @var InvMenu */
	private InvMenu $menu;

	/** @var int $rarity */
	private int $rarity;

    /** @var int $time */
    public int $time = 6;

    /**
     * @param Player $player
     * @param int $clicked
     * @param InvMenu $menu
     * @param int $rarity
     */
	public function __construct(Player $player, int $clicked, InvMenu $menu, int $rarity)
	{
		$this->player = $player;
		$this->clicked = $clicked;
		$this->menu = $menu;
		$this->rarity = $rarity;
	}


	public function onRun(): void
	{
		if ($this->player === null || !$this->player->isOnline() || $this->player->isClosed()) {
			$this->getHandler()->cancel();
			return;
		}
		$player = $this->player;
		if($this->time >= 0){
			--$this->time;
		}
		if($this->time === 0){
			unset(self::$recieve[$player->getName()]);
			foreach ([30,31,32,23,22,21,14,13,12] as $slot){
				$item = $this->menu->getInventory()->getItem($slot);
				$this->player->getInventory()->addItem($item);
			}
			$this->getHandler()->cancel();
		}
		if ($this->clicked >= 9 && $this->time >= 1) {
			$outside = range(0, 53);
			foreach ($outside as $grid) {
				if (!in_array($grid, [30, 31, 32, 23, 22, 21, 14, 13, 12,49])) {
					$this->menu->getInventory()->setItem($grid, ItemFactory::getInstance()->get(ItemIds::STAINED_GLASS_PANE, rand(1,10), 1)->setCustomName("§r§8 ' "));
					Loader::playSound($this->player,"note.harp");
				}
			}
		}
		if($this->time === 1){
			$outside = range(0,53);
			foreach ($outside as $grid) {
				if (!in_array($grid, [30, 31, 32, 23, 22, 21, 14, 13, 12, 49])) {
					$this->menu->getInventory()->setItem($grid, ItemFactory::getInstance()->get(ItemIds::STAINED_GLASS_PANE, 15, 1)->setCustomName("§r§8 ' "));
				}
				$item = ItemFactory::getInstance()->get(ItemIds::ENDER_CHEST, 0, 1)->setCustomName("§r§f§l???");
				$item->setLore([
					'§r§7Click to redeem an item from',
					'§r§7your ' . $this->menu->getName() . " Sage Crate!"
				]);
				$item->getNamedTag()->setString("lastitem", "true");
				$this->menu->getInventory()->setItem(49, $item);
				$player->getWorld()->addSound($player->getLocation(), new XpLevelUpSound(1000));
			}
		}
	}
}