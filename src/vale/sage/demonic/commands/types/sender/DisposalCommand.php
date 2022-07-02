<?php

declare(strict_types=1);

namespace vale\sage\demonic\commands\types\sender;
use CortexPE\Commando\BaseCommand;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\player\Player;
use vale\sage\demonic\addons\types\inventorys\DisposalInventory;
use vale\sage\demonic\Loader;

class DisposalCommand extends Command
{

	public function execute(CommandSender $sender, string $aliasUsed, array $args): void
	{
		if (!$sender instanceof Player) {
			return;
		}
		DisposalInventory::open($sender);
	}
}