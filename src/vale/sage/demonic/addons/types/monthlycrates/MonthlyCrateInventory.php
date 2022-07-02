<?php
namespace vale\sage\demonic\addons\types\monthlycrates;

use BlockHorizons\Fireworks\item\Fireworks;
use muqsit\invmenu\InvMenu;
use muqsit\invmenu\transaction\DeterministicInvMenuTransaction;
use muqsit\invmenu\type\InvMenuTypeIds;
use pocketmine\block\VanillaBlocks;
use pocketmine\entity\effect\VanillaEffects;
use pocketmine\entity\Location;
use pocketmine\item\ItemFactory;
use pocketmine\item\ItemIds;
use pocketmine\item\VanillaItems;
use pocketmine\player\Player;
use pocketmine\world\sound\AnvilFallSound;
use vale\sage\demonic\addons\types\monthlycrates\task\MonthlyCrateTickTask;
use vale\sage\demonic\addons\types\monthlycrates\task\SlotShuffleTask;
use vale\sage\demonic\Loader;
use vale\sage\demonic\rewards\redeemable\RedeemableAPI;
use vale\sage\demonic\Utils;

class MonthlyCrateInventory{

    /** @var int */
	public static int $clicked = 0;

	/**
	 * @param Player $player
	 * @param string $type
	 * @param int $rarity
	 */
	public static function open(Player $player, string $type, int $rarity)
	{
		Loader::playSound($player,"note.bd");
		self::$clicked = 0;
		MonthlyCrateTickTask::$recieve[$player->getName()] = $player;
		$menu = InvMenu::create(InvMenuTypeIds::TYPE_DOUBLE_CHEST)->
	setName(RedeemableAPI::getMCByID($rarity));
		$outside = range(0,53);
		$item = ItemFactory::getInstance()->get(ItemIds::ENDER_CHEST,0,1)->setCustomName("§r§f§l???");
		$item->setLore([
			'§r§7Click to redeem an item from',
			'§r§7your '. $menu->getName() . " Sage Crate!"
		]);
		$item->getNamedTag()->setString("clickable","true");
		$item2 = ItemFactory::getInstance()->get(ItemIds::STAINED_GLASS,14,1)->setCustomName("§r§c§l???");
		$item2->setLore([
			'§r§7Redeem all your other rewards to',
			'§r§7unlock this special "META" item.'
		]);
		$menu->getInventory()->setContents([
				30 => $item,
				31 => $item,
				32 => $item,
				23 => $item,
				22 => $item,
				21 => $item,
				14 => $item,
				13 => $item,
				12 => $item,
				49 => $item2
			]
		);
		foreach ($outside as $grid) {
			if (!in_array($grid, [30, 31, 32, 23, 22, 21, 14, 13, 12, 49])) {
				$menu->getInventory()->setItem($grid, ItemFactory::getInstance()->get(ItemIds::STAINED_GLASS_PANE, 15, 1)->setCustomName("§r§8 ' "));
			}
		}
		$menu->send($player);
		$menu->setInventoryCloseListener(function ()use($player, $rarity){
			if(!isset(MonthlyCrateTickTask::$recieve[$player->getName()])) {
				if ($rarity === 0) {
					$t = $player->getLocation();
					Utils::spawnFirework(new Location($player->getLocation()->getX(), $t->getY() + 1, $player->getLocation()->getZ(), $player->getWorld(), 0, 0), Fireworks::COLOR_RED);
					Utils::spawnFirework(new Location($player->getLocation()->getX(), $player->getLocation()->getY() + 1.5, $player->getLocation()->getZ(), $player->getWorld(), 0, 0), Fireworks::COLOR_WHITE);
					Utils::spawnFirework(new Location($player->getLocation()->getX(), $player->getLocation()->getY() + 2, $player->getLocation()->getZ(), $player->getWorld(), 0, 0), Fireworks::COLOR_BLUE);
				}
			}
		});
		$menu->setListener(InvMenu::readonly(function (DeterministicInvMenuTransaction $transaction) use ($player, $menu, $rarity): void {
           if($transaction->getItemClicked()->getNamedTag()->getString("clickable","") !== ""){
			   self::$clicked++;
			   Loader::getInstance()->getScheduler()->scheduleRepeatingTask(new SlotShuffleTask($player,$transaction->getAction()->getSlot(),self::$clicked,$menu, $rarity),15);
		   }
		   if($transaction->getItemClicked()->getCustomName() === "§r§c§l???"){
			   $player->sendMessage("§r§l§c(!) §r§cYou cannot reveal the final reward until all previous items have been looted!");
			   $player->getWorld()->addSound($player->getLocation(),new AnvilFallSound());
			   return;
		   }
			if($transaction->getItemClicked()->getNamedTag()->getString("lastitem","") !== ""){
				$random = RedeemableAPI::getMCRewardsById($rarity);
				$menu->getInventory()->setItem($transaction->getAction()->getSlot(),$random[array_rand($random)]);
				Loader::playSound($player, "mob.enderdragon.flap", 1);
				$player->getInventory()->addItem($menu->getInventory()->getItem($transaction->getAction()->getSlot()));
			}
		}));

	}

}