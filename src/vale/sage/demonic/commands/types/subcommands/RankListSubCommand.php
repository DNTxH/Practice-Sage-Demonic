<?php

namespace vale\sage\demonic\commands\types\subcommands;

use CortexPE\Commando\BaseCommand;
use CortexPE\Commando\BaseSubCommand;
use pocketmine\command\CommandSender;
use pocketmine\player\Player;
use pocketmine\Server;
use vale\sage\demonic\Loader;
use pocketmine\utils\TextFormat;

class RankListSubCommand extends BaseSubCommand {

	protected function prepare(): void
	{
	}

	public function onRun(CommandSender $sender, string $aliasUsed, array $args): void
	{
		if (!$sender instanceof Player) return;
		$ranks = Loader::getInstance()->getRankManager()->getAll();
		$sender->sendMessage(TextFormat::GREEN . "Available ranks: " . implode(", ", $ranks));
	}
}