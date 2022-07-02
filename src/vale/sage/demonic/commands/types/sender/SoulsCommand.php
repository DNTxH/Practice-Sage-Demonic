<?php

namespace vale\sage\demonic\commands\types\sender;

use CortexPE\Commando\args\RawStringArgument;
use CortexPE\Commando\BaseCommand;
use pocketmine\command\CommandSender;
use pocketmine\player\Player;
use pocketmine\Server;
use vale\sage\demonic\commands\types\subcommands\ReclaimResetSubCommand;
use vale\sage\demonic\Loader;
use vale\sage\demonic\ranks\rank\RankIDS;
use vale\sage\demonic\ranks\RankManager;

class SoulsCommand extends BaseCommand
{

	protected function prepare(): void
	{
	}

	public function onRun(CommandSender $sender, string $aliasUsed, array $args): void
	{
		if (!$sender instanceof Player) return;
		$session = Loader::getInstance()->getSessionManager()->getSession($sender);
		$souls = $session->getSouls();
		$sender->sendMessage("\n§l§cSoul level(s): §r§7$souls\n§7Increase this level by killing mobs, players or mining.\n\n");
	}
}