<?php
namespace vale\sage\demonic\factions\command\subcommands;

use CortexPE\Commando\args\IntegerArgument;
use CortexPE\Commando\args\RawStringArgument;
use CortexPE\Commando\BaseSubCommand;
use pocketmine\player\Player;
use pocketmine\command\CommandSender;
use pocketmine\Server;
use vale\sage\demonic\Loader;


class ColeaderSubCommand extends BaseSubCommand
{

	protected function prepare(): void
	{
		$this->registerArgument(0, new RawStringArgument("name",true));
	}

	public function onRun(CommandSender $sender, string $commandLabel, array $args): void {
		if(!$sender instanceof Player) {
			return;
		}
		if(!isset($args["name"])){
			$sender->sendMessage("§r§c§l(!) §r§cInvalid usage, example: \n §r§b/f coleader §r§d<name:string>");
			return;
		}
		$session = Loader::getInstance()->getSessionManager()->getSession($sender);
		if($session->getFaction() === null) {
			$sender->sendMessage("§r§c§l(!) §r§cYou do not have a Faction!");
			return;
		}
		if(!$session->getFaction()->isMember($args["name"])) {
			$sender->sendMessage("§r§c§l(!) §r§cPlayer {$args["name"]} is not from your faction!");
			return;
		}
		$session->getFaction()->setRank($args["name"], "coleader");
		$p = Server::getInstance()->getPlayerExact($args["name"]);
		if($p instanceof Player) {
			$p->sendMessage("§r§c§l(!) §r§cYou were promoted in your faction to CoLeader§c!");
		}
		$sender->sendMessage("§r§c§l(!) §r§cSuccessfully promoted {$args["name"]}!");
	}
}