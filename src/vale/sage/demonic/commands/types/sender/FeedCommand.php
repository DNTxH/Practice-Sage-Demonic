<?php

declare(strict_types=1);

namespace vale\sage\demonic\commands\types\sender;
use CortexPE\Commando\BaseCommand;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\player\Player;
use pocketmine\Server;
use vale\sage\demonic\addons\types\envoys\events\JackPotEvent;
use vale\sage\demonic\addons\types\inventorys\EnchanterInventory;
use vale\sage\demonic\addons\types\monthlycrates\MonthlyCrateInventory;
use vale\sage\demonic\Loader;

class FeedCommand extends Command
{

	public function execute(CommandSender $sender, string $aliasUsed, array $args): void
	{
		if (!$sender instanceof Player) {
			return;
		}
		$sender->sendMessage("§r§e§l/feed: §r§eyour hunger has been cleared.");
		$sender->getHungerManager()->setFood(20);
	}
}