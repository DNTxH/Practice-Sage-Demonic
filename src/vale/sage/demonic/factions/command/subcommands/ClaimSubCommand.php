<?php
namespace vale\sage\demonic\factions\command\subcommands;
use CortexPE\Commando\args\RawStringArgument;
use CortexPE\Commando\BaseSubCommand;
use pocketmine\player\Player;
use pocketmine\command\CommandSender;
use pocketmine\Server;
use vale\sage\demonic\factions\FactionManager;
use vale\sage\demonic\Loader;


class ClaimSubCommand extends BaseSubCommand
{

	protected function prepare(): void
	{
	}

	public function onRun(CommandSender $sender, string $commandLabel, array $args): void {
		if(!$sender instanceof Player) {
			return;
		}
		$session = Loader::getInstance()->getSessionManager()->getSession($sender);
		
		if($session->getFaction() === null) {
			$sender->sendMessage("§r§c§l(!) §r§cYou do not have a faction!");
			return;
		}
		if(!$session->getFaction()->tryClaimChunk( $sender->getFloorX() >> 4, $sender->getFloorZ() >> 4, $sender)) {
			return;
		}
	}
}