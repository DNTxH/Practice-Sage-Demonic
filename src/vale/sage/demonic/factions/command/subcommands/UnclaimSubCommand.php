<?php
namespace vale\sage\demonic\factions\command\subcommands;

use CortexPE\Commando\args\IntegerArgument;
use CortexPE\Commando\args\RawStringArgument;
use CortexPE\Commando\BaseSubCommand;
use pocketmine\player\Player;
use pocketmine\command\CommandSender;


class UnclaimSubCommand extends BaseSubCommand
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
		$chunkX = $sender->getFloorX() >> 4;
		$chunkZ = $sender->getFloorZ() >> 4;

		if(FactionManager::getInstance()->getFactionByChunk($chunkX, $chunkZ) !== $session->getFaction()->getId()) {
			$sender->sendMessage(Core::ERROR_PREFIX . "Chunk is not yours!");
			return;
		}
		$session->getFaction()->loseChunk($chunkX, $chunkZ);
		$sender->sendMessage("§aChunk $x, $z unclaimed!");
	}
}