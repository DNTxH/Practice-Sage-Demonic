<?php

declare(strict_types=1);

namespace vale\sage\demonic\commands\types\sender;
use CortexPE\Commando\BaseCommand;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\player\Player;
use vale\sage\demonic\addons\types\envoys\events\JackPotEvent;
use vale\sage\demonic\addons\types\inventorys\EnchanterInventory;
use vale\sage\demonic\addons\types\monthlycrates\MonthlyCrateInventory;
use vale\sage\demonic\Loader;

class AgeCommand extends Command
{

	public function execute(CommandSender $sender, string $aliasUsed, array $args): void
	{
		if (!$sender instanceof Player) {
			return;
		}
		$last = time() - Loader::getInstance()->getConfig()->get("map_age");
		$previous = "§r§6§lSage §r§7§o(Map #1) §r§eis §r§6" . Loader::secondsToTime((int)$last) . " §r§eold!";
		$sender->sendMessage($previous);
	}
}