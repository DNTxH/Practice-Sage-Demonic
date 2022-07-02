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

class LootboxTask extends Task{

	public const OUTSIDE = [9,17];

	public const INSIDE = [10,11,12,13,14,15,16];

	public $duration = 15;

	public function __construct(
		private Player $player,
		private ?InvMenu $menu = null,
		private int $tier = 0,
	){
		$name = "";
		if($this->tier === 0) $name = "§r§7SAGE CRATE: Thanks_Giving2021";
		$this->menu = InvMenu::create(InvMenuTypeIds::TYPE_CHEST);
		$this->menu->setName($name);
		$null = ItemFactory::getInstance()->get(36, 0);
		$this->menu->getInventory()->setItem(9, $null);
		$this->menu->getInventory()->setItem(17, $null);
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

		$items = RedeemableAPI::getLootBoxRewards($this->tier);
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
			$id = $this->tier;
			$message = "§r§6§l(!) §r§6{$this->player->getName()} opened a " . RedeemableAPI::getLootBoxNameById($id) . " §r§6and recieved:";
			Server::getInstance()->broadcastMessage($message);
			foreach ($this->menu->getInventory()->getContents(false) as $slot => $content) {
				Server::getInstance()->broadcastMessage("§r§6§l* §r§f{$content->getCount()}x " . $content->getCustomName());
			}
			$this->getHandler()->cancel();
		}
	}
}