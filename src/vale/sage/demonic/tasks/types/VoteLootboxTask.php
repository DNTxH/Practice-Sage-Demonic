<?php
namespace vale\sage\demonic\tasks\types;

use muqsit\invmenu\InvMenu;
use muqsit\invmenu\type\InvMenuTypeIds;
use pocketmine\item\enchantment\VanillaEnchantments;
use pocketmine\item\ItemFactory;
use pocketmine\player\Player;
use pocketmine\scheduler\Task;
use pocketmine\Server;
use pocketmine\world\sound\XpLevelUpSound;
use vale\sage\demonic\Loader;
use vale\sage\demonic\rewards\redeemable\RedeemableAPI;

class VoteLootboxTask extends Task{

	public const OUTSIDE = [4];

	public const INSIDE = [0,1,2,3,5,6,7,8];

	public $duration = 15;

	public function __construct(
		private Player $player,
		private ?InvMenu $menu = null,
	){
		$name = "§r§7CRATE: VOTE";
		$this->menu = InvMenu::create(Loader::TYPE_DISPENSER);
		$this->menu->setName($name);
		$null = ItemFactory::getInstance()->get(36, 0);
		$this->menu->getInventory()->setItem(4, $null);
		$this->menu->send($this->player);
		$this->menu->setListener(InvMenu::readonly());
	}

	public function onRun(): void
	{
		--$this->duration;
		if ($this->player === null || !$this->player->isOnline() || $this->player->isClosed()) {
			$this->getHandler()->cancel();
			return;
		}

		foreach (self::OUTSIDE as $OUTSIDE_SLOT) {
			$item = $this->menu->getInventory()->getItem($OUTSIDE_SLOT);
			$item->setCount($this->duration);
			$this->menu->getInventory()->setItem($OUTSIDE_SLOT, $item);
		}

		$items = RedeemableAPI::getLootBoxRewards(0);
		if ($this->duration <= 13 && $this->duration > 0)
			foreach (self::INSIDE as $SLOT) {
				Loader::playSound($this->player, "random.click", 0.4, 0.5);
				$this->menu->getInventory()->setItem($SLOT, $items[array_rand($items)]);
			}
		if ($this->duration <= 0) {
			foreach ($this->menu->getInventory()->getContents(false) as $slot => $content) {
				if (in_array($slot, self::INSIDE)) {
					$this->player->getInventory()->addItem($this->menu->getInventory()->getItem($slot));
				}
			}
			$this->player->getLocation()->getWorld()->addSound($this->player->getLocation(), new XpLevelUpSound(100));
			$this->player->getNetworkSession()->getInvManager()->onClientRemoveWindow($this->player->getNetworkSession()->getInvManager()->getCurrentWindowId());
			$this->getHandler()->cancel();
		}
	}
}