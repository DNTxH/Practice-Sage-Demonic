<?php

declare(strict_types=1);

namespace vale\sage\demonic\commands\types\sender;
use CortexPE\Commando\BaseCommand;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\player\Player;
use vale\sage\demonic\addons\types\tinkerer\TinkerInventory;
use vale\sage\demonic\Loader;

class TinkererCommand extends Command
{

	public function execute(CommandSender $sender, string $aliasUsed, array $args): void
	{
		if (!$sender instanceof Player) {
			return;
		}
		TinkerInventory::open($sender);
	}
}