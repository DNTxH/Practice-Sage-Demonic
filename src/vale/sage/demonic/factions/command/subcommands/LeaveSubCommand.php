<?php
namespace vale\sage\demonic\factions\command\subcommands;

use CortexPE\Commando\args\IntegerArgument;
use CortexPE\Commando\args\TestArg;
use CortexPE\Commando\BaseSubCommand;
use pocketmine\player\Player;
use pocketmine\command\CommandSender;
use pocketmine\Server;
use vale\sage\demonic\Loader;


class LeaveSubCommand extends BaseSubCommand
{

	protected function prepare(): void
	{
	}

	public function onRun(CommandSender $sender, string $aliasUsed, array $args): void
	{
		if (!$sender instanceof Player) {
			return;
		}
		$session = Loader::getInstance()->getSessionManager()->getSession($sender);

		if($session->getFaction() == null) {
			$sender->sendMessage("§r§c§l(!) §r§cNo faction!");
			return;
		}
		if($session->getFaction()->getLeader()->getName() === $sender->getName()) {
			$sender->sendMessage("§cYou can't leave a faction you're the owner of!");
			return;
		}
		$session->getFaction()->announce("§b" . $sender->getName() .  " left the faction!");
		$session->getFaction()->kick($sender->getName());
		$sender->sendMessage("§aSuccessfully left faction!");
	}
}