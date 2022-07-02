<?php
namespace vale\sage\demonic\factions\command\subcommands;

use CortexPE\Commando\args\IntegerArgument;
use CortexPE\Commando\args\TestArg;
use CortexPE\Commando\BaseSubCommand;
use pocketmine\player\Player;
use pocketmine\command\CommandSender;
use pocketmine\Server;
use vale\sage\demonic\Loader;


class ListSubCommand extends BaseSubCommand
{

	protected function prepare(): void
	{
	}

	public function onRun(CommandSender $sender, string $commandLabel, array $args): void {
		if(!$sender instanceof Player) {
			return;
		}
		$facs = [];

		foreach(FactionManager::getInstance()->getFactions() as $f) {
			if(count($f->getOnlinePlayers()) >= 0) {
				$facs[$f->getName()] = count($f->getOnlinePlayers());
			}
		}
		$m = "§b --Faction List-- \n";

		arsort($facs);

		foreach($facs as $name => $amount) {
			$m .= "§f - §6$name - §e$amount Players\n";
		}
		$sender->sendMessage($m);
	}
}