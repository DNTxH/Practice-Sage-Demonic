<?php
namespace vale\sage\demonic\tasks\types;


use muqsit\invmenu\InvMenu;
use pocketmine\item\ItemFactory;
use pocketmine\player\Player;
use pocketmine\scheduler\Task;
use pocketmine\Server;
use pocketmine\world\sound\XpLevelUpSound;
use vale\sage\demonic\Loader;
use vale\sage\demonic\rewards\redeemable\RedeemableAPI;


class SatchelBoxTask extends Task
{
	public int $time = 15;

	public const DISPENSER_SLOTS = [1, 3, 4, 5, 7];

	public const OUTSIDE_SLOTS = [0, 2, 8, 6];

	public InvMenu $menu;

	public Player $player;

	public ?int $rarity = 0;

	public function __construct(Player $player, InvMenu $menu = null, int $rarity = 0)
	{
		$this->player = $player;
		$this->menu = InvMenu::create(Loader::TYPE_DISPENSER);
		$menu = $this->menu;
		$this->rarity = $rarity;
		$null = ItemFactory::getInstance()->get(36, 0);
		$menu->getInventory()->setItem(0, $null);
		$menu->getInventory()->setItem(2, $null);
		$menu->getInventory()->setItem(6, $null);
		$menu->getInventory()->setItem(8, $null);
		$menu->setListener(InvMenu::readonly());
		$menu->send($player);
	}

	public function onRun(): void
	{
		--$this->time;
		if ($this->player === null || !$this->player->isOnline() || $this->player->isClosed()) {
			$this->getHandler()->cancel();
			return;
		}

		foreach (self::OUTSIDE_SLOTS as $OUTSIDE_SLOT) {
			$item = $this->menu->getInventory()->getItem($OUTSIDE_SLOT);
			$item->setCount($this->time);
			$this->menu->getInventory()->setItem($OUTSIDE_SLOT, $item);
		}
		$items = RedeemableAPI::getSatchelRewards($this->rarity);
		if ($this->time <= 13 && $this->time > 0)
			foreach (self::DISPENSER_SLOTS as $SLOT) {
				Loader::playSound($this->player, "random.orb", 0.4, 0.5);
				$this->menu->getInventory()->setItem($SLOT, $items[array_rand($items)]);
			}
		if ($this->time <= 0) {
			foreach ($this->menu->getInventory()->getContents(false) as $slot => $content) {
				if (in_array($slot, self::DISPENSER_SLOTS)) {
					$this->player->getInventory()->addItem($this->menu->getInventory()->getItem($slot));
				}
			}
			$this->player->getLocation()->getWorld()->addSound($this->player->getLocation(), new XpLevelUpSound(100));
			$this->player->getNetworkSession()->getInvManager()->onClientRemoveWindow($this->player->getNetworkSession()->getInvManager()->getCurrentWindowId());
			$id = $this->rarity;
			$message = "§r§6§l(!) §r§6{$this->player->getName()} opened a " . RedeemableAPI::getSatchelNameByID($id) . " §r§6and recieved:";
			Server::getInstance()->broadcastMessage($message);
			foreach ($this->menu->getInventory()->getContents(false) as $slot => $content) {
				Server::getInstance()->broadcastMessage("§r§6§l* §r§f{$content->getCount()}x " . $content->getCustomName());
			}
			$this->getHandler()->cancel();
		}
	}
}