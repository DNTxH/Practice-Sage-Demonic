<?php
namespace vale\sage\demonic\factions\command\subcommands;

use CortexPE\Commando\args\IntegerArgument;
use CortexPE\Commando\args\RawStringArgument;
use CortexPE\Commando\BaseSubCommand;
use pocketmine\player\Player;
use pocketmine\command\CommandSender;


class SetHomeSubCommand extends BaseSubCommand
{

	protected function prepare(): void
	{
	}

	public function onRun(CommandSender $sender, string $aliasUsed, array $args): void
	{
		if(FactionManager::getInstance()->getFactionByChunk($sender->getFloorX() >> 4, $sender->getFloorZ() >> 4) !== $fac->getId()){
			$sender->sendMessage(TextFormat::RED . "You can only set faction home in your claimed chunks!");
			return;
		}
		$fac->setHome($sender->getPosition());
		$sender->sendMessage(TextFormat::GREEN . "Successfully set home to §6" . $sender->x . ", " . $sender->y . ", " . $sender->z . "§a!");
	}
}