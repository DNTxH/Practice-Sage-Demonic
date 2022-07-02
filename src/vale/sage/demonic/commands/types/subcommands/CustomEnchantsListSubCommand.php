<?php
namespace vale\sage\demonic\commands\types\subcommands;

use CortexPE\Commando\args\RawStringArgument;
use CortexPE\Commando\BaseSubCommand;
use pocketmine\player\Player;
use pocketmine\command\CommandSender;

class CustomEnchantsListSubCommand extends BaseSubCommand
{
	public const TYPES = ["weapons", "armour"];

	protected function prepare(): void
	{
		$this->registerArgument(0, new RawStringArgument("type", true));
	}


	public function onRun(CommandSender $sender, string $aliasUsed, array $args): void
	{
		if (!$sender instanceof Player) {
			return;
		}
		if (!isset($args["type"])) {
			$sender->sendMessage("§r§c§l(!) §r§cThis command is currently disabled.");
		}
	}
}