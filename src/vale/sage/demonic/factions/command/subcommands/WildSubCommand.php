<?php
namespace vale\sage\demonic\factions\command\subcommands;

use CortexPE\Commando\args\IntegerArgument;
use CortexPE\Commando\args\RawStringArgument;
use CortexPE\Commando\BaseSubCommand;
use pocketmine\player\Player;
use pocketmine\command\CommandSender;


class FactionWhoSubCommand extends BaseSubCommand
{

	protected function prepare(): void
	{
		$this->registerArgument(0, new RawStringArgument("name",true));
	}

	public function onRun(CommandSender $sender, string $aliasUsed, array $args): void
	{
		if(!isset($args["name"])){
			$sender->sendMessage("§r§c§l(!) §r§cInvalid usage, example: \n §r§b/f who | info §r§d<faction tag:string>");
			return;
		}
		if(isset($args["name"])){
			$faction = $args["name"];
			$sender->sendMessage("TEST: $faction");
		}
	}
}