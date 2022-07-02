<?php
namespace vale\sage\demonic\factions\command\subcommands;

use CortexPE\Commando\args\IntegerArgument;
use CortexPE\Commando\args\TestArg;
use CortexPE\Commando\BaseSubCommand;
use pocketmine\player\Player;
use pocketmine\command\CommandSender;
use pocketmine\Server;
use vale\sage\demonic\Loader;


class CoordsSubCommand extends BaseSubCommand
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
		$session->getFaction()->announce("§l§6 " . $sender->getName() . "'s COORDS: 
			X:" . $sender->getPosition()->getX() .
			"Y:" . $sender->getPosition()->getY() .
			"Z:" . $sender->getPosition()->getZ()
		);
	}
}