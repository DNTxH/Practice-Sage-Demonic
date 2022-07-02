<?php
namespace vale\sage\demonic\commands\types\sender;

use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\command\utils\CommandException;
use pocketmine\event\player\PlayerItemUseEvent;
use pocketmine\item\ItemFactory;
use pocketmine\item\ItemIds;
use pocketmine\player\Player;
use vale\sage\demonic\Loader;

class PotsCommand extends Command
{

	/**
	 * @param CommandSender $sender
	 * @param string $commandLabel
	 * @param array $args
	 * @return void
	 */
	public function execute(CommandSender $sender, string $commandLabel, array $args)
	{
		if (!$sender instanceof Player) {
			return;
		}
		$session = Loader::getInstance()->getSessionManager()->getSession($sender);
		$price = 327.82;
		$i = 0;
		foreach ($sender->getInventory()->getContents(true) as $content => $slot) {
			if ($slot->getId() === ItemIds::AIR) $i++;
		}
		$price = $price * $i;
		if($session->getBalance() < $price){
			$session->getPlayer()->sendMessage("§r§c§l(!) §r§cYou do not have the sufficient funds to purchase this.");
			$session->getPlayer()->sendMessage("§r§7Each Potion costs roughly $327.82, You only have $". number_format($session->getBalance(),2));
			return;
		}
		if($i <= 5){
			$sender->sendMessage("§r§c§l(!) §r§cYou do not have enough open inventory slot(s).");
			$sender->sendMessage("§r§7You must have at minimum 5 slot(s) available!");
			return;
		}
			$sender->sendMessage("§r§c§l/pots:");
			$sender->sendMessage("§r§7Your inventory is now filled with Instant Health II potions.");
			$sender->sendMessage("§r§c§l+ §r§fSplash Potion of Healing II");
			$sender->sendMessage("§r§c§l- §r§f$" . number_format($price, 2));
			$sender->getInventory()->addItem(ItemFactory::getInstance()->get(ItemIds::SPLASH_POTION, 22, $i));
			$session->setBalance($session->getBalance() - $price);
			Loader::playSound($sender, "mob.villager.haggle");
		}
}