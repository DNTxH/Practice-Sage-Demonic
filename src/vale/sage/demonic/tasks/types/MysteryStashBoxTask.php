<?php
namespace vale\sage\demonic\tasks\types;

use muqsit\invmenu\InvMenu;
use pocketmine\item\ItemFactory;
use pocketmine\item\ItemIds;
use pocketmine\player\Player;
use pocketmine\scheduler\Task;
use pocketmine\Server;
use pocketmine\world\sound\XpLevelUpSound;
use vale\sage\demonic\Loader;
use vale\sage\demonic\rewards\redeemable\RedeemableAPI;

class MysteryStashBoxTask extends Task{

	public const GRID = [
		0,1,2,3,5,6,7,8
	];

	public function __construct(
		private Player $player,
		private int $duration = 25,
		private ?InvMenu $menu = null,
	){
     $this->menu = InvMenu::create(Loader::TYPE_DISPENSER);
	 $this->menu->send($this->player);
	 $this->menu->setListener(InvMenu::readonly());
	}

	public function getMenu(): InvMenu{
		return $this->menu;
	}

	public function getDuration(): int{
		return $this->duration;
	}

	public function onRun(): void
	{
		if ($this->player === null || !$this->player->isOnline() || $this->player->isClosed()) {
			$this->getHandler()->cancel();
			return;
		}
		--$this->duration;
		foreach (self::GRID as $slot) {
			$this->getMenu()->getInventory()->setItem($slot, ItemFactory::getInstance()->get(ItemIds::STAINED_GLASS, rand(1, 10), 1)->setCustomName(" §r§8''"));
			Loader::playSound($this->player,"fall.copper",1,0.45);
		}
		$this->getMenu()->getInventory()->setItem(4, RedeemableAPI::getRandomStashBox());
		if ($this->duration <= 0) {
			$slot = 4;
			$this->player->getInventory()->addItem($this->menu->getInventory()->getItem($slot));
			$this->player->getLocation()->getWorld()->addSound($this->player->getLocation(), new XpLevelUpSound(100));
			$this->player->getNetworkSession()->getInvManager()->onClientRemoveWindow($this->player->getNetworkSession()->getInvManager()->getCurrentWindowId());
			$message = "§r§6§l(!) §r§6{$this->player->getName()} opened a " . "§r§5§lMystery Stash Box §r§7(Right-Click) " . " §r§6and recieved: \n §r§6§l* §r§f{$this->menu->getInventory()->getItem(4)->getCount()}x {$this->menu->getInventory()->getItem(4)->getCustomName()}";
			Server::getInstance()->broadcastMessage($message);
			$this->getHandler()->cancel();
		}
	}
}