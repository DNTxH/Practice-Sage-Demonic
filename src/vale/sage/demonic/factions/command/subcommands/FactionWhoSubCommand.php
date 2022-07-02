<?php
namespace vale\sage\demonic\factions\command\subcommands;

use CortexPE\Commando\args\IntegerArgument;
use CortexPE\Commando\args\RawStringArgument;
use CortexPE\Commando\BaseSubCommand;
use pocketmine\player\Player;
use pocketmine\command\CommandSender;
use vale\sage\demonic\Loader;


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
		$fac = Loader::getInstance()->getFactionsManager()->getFaction($args["name"]);
		
		if($fac == null) {
			$sender->sendMessage("§r§c§l(!) §r§cFaction not found");
			return;
		}
		$sender->sendMessage("§r§3-------- §r" . $fac->getName() . " ---------\n
			§2* §3Owner: " . $fac->getLeader() . "\n
			§2* §3Description: " . $fac->getDescription() . "\n
			§2* §3Land / Power / Max Power: " . count($fac->claims) . " / " . " / " . count($fac->getAllMembers()) . "\n
			§2* §3Founded: " . $fac->getCreationDate() . "\n
			§2* §3Value: " . $fac->getValue() . "\n
			§2* §3Allies: " . "\n
			§2* §3Enemies: " . "\n
			§2* §3Online Members: " . "\n
			§2* §3Offline Members: " . "\n
		\n§a");
	}
}