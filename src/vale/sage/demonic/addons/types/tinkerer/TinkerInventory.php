<?php
namespace vale\sage\demonic\addons\types\tinkerer;

use muqsit\invmenu\InvMenu;
use muqsit\invmenu\transaction\DeterministicInvMenuTransaction;
use muqsit\invmenu\type\InvMenuTypeIds;
use pocketmine\item\Item;
use pocketmine\item\ItemFactory;
use pocketmine\item\ItemIds;
use pocketmine\scheduler\ClosureTask;
use vale\sage\demonic\addons\types\customenchants\factory\EnchantFactory;
use pocketmine\player\Player;
use vale\sage\demonic\Loader;
use vale\sage\demonic\rewards\Rewards;


class TinkerInventory
{
    /** @var array */
	public static array $items = [];

    /** @var array */
	public static array $clicked = [];

    /**
     * @param Player $player
     * @return void
     */
	public static function open(Player $player)
	{
		self::$clicked = [];
		self::$items = [];
		$menu = InvMenu::create(InvMenuTypeIds::TYPE_DOUBLE_CHEST);
		$item = ItemFactory::getInstance()->get(ItemIds::STAINED_GLASS_PANE, 4);
		$item->setCustomName("§r§e§lDeposit All");
		$item->setLore([
			'§r§7Click to deposit all tinkerable items'
		]);
		$menu->getInventory()->setItem(0, $item);
		$item2 = ItemFactory::getInstance()->get(ItemIds::STAINED_GLASS_PANE, 5, 1);
		$item2->setCustomName("§r§eClick to ACCEPT Trade");
		$menu->getInventory()->setItem(53, $item2);
		$item3 = ItemFactory::getInstance()->get(ItemIds::STAINED_GLASS_PANE, 2, 1);
		$item3->setCustomName("§r§cClick to Cancel Trade");
		$menu->getInventory()->setItem(8, $item3);
		$menu->send($player);
		Loader::playSound($player, "mob.villager.idle", 1);
		$menu->setInventoryCloseListener(function () use ($player, $menu) {
			if (!isset(self::$clicked[$player->getName()])) {
			Loader::playSound($player, "mob.chicken.plop");
			$player->sendMessage("§r§aTrade Accepted...");
			foreach ($menu->getInventory()->getContents() as $item) {
					if ($item->hasEnchantments() && $item->getId() !== ItemIds::ENCHANTED_BOOK) {
						$enchs = count($item->getEnchantments());
						$value = 2 * $enchs;
						$menu->getInventory()->removeItem($item);
						$half = $value / 2;
						$bottle = Rewards::createXPBottle($player, rand(40, 4000), $value);
						$player->getInventory()->addItem($bottle);
					}
					if($item->hasEnchantments() && $item->getId() === ItemIds::ENCHANTED_BOOK){
						foreach ($item->getEnchantments() as $instance){
							$rarity = $instance->getType()->getRarity();
							$player->getInventory()->addItem(EnchantFactory::giveDust($rarity,rand(1,2), $player));
						}
					}
				}
			}
		});
		$menu->setListener(InvMenu::readonly(function (DeterministicInvMenuTransaction $transaction) use ($player, $menu): void {
			if ($transaction->getItemClicked()->getCustomName() === "§r§e§lDeposit All") {
				$i = 0;
				foreach ($player->getInventory()->getContents() as $item) {
					if ($item->hasEnchantments() && in_array($item->getId(),[276,310,311,312,313,279, ItemIds::ENCHANTED_BOOK])) {
						$player->getInventory()->remove($item);
						$menu->getInventory()->addItem($item);
						$i++;
						self::$items[$i] = $item->jsonSerialize();
					}
				}
			}
			if ($transaction->getItemClicked()->getCustomName() === "§r§cClick to Cancel Trade") {
				self::$clicked[$player->getName()] = $player;
				foreach (self::$items as $id => $itemz) {
					$lol = Item::jsonDeserialize($itemz);
					$player->getInventory()->addItem($lol);
				}
					Loader::getInstance()->getScheduler()->scheduleDelayedTask(new ClosureTask(function () use ($player, $transaction, $menu): void {
						$player->getNetworkSession()->getInvManager()->onClientRemoveWindow($player->getNetworkSession()->getInvManager()->getCurrentWindowId());
						$player->sendMessage("§r§cTrade Cancelled...");
					}), 5);
				}


			if ($transaction->getItemClicked()->getCustomName() === "§r§eClick to ACCEPT Trade") {
				Loader::getInstance()->getScheduler()->scheduleDelayedTask(new ClosureTask(function () use ($player, $transaction, $menu): void {
					$player->getNetworkSession()->getInvManager()->onClientRemoveWindow($player->getNetworkSession()->getInvManager()->getCurrentWindowId());
				}), 5);
			}
		}));
	}
}
