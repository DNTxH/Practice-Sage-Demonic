<?php
namespace vale\sage\demonic\factions\command\subcommands;
use CortexPE\Commando\args\RawStringArgument;
use CortexPE\Commando\BaseSubCommand;
use pocketmine\player\Player;
use pocketmine\command\CommandSender;
use pocketmine\Server;
use vale\sage\demonic\factions\FactionManager;
use vale\sage\demonic\Loader;


class KickSubCommand extends BaseSubCommand
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
			$sender->sendMessage("§r§c§l(!) §r§cInvalid usage, example: \n §r§b/f kick §r§d<faction tag:string>");
			return;
		}
		$session = Loader::getInstance()->getSessionManager()->getSession($sender);
		if($session->getFaction() !== null) {
			$sender->sendMessage("§r§c§l(!) §r§cYou must leave your current faction first!");
			return;
		}
		if($args["name"] == $sender->getName()) {
			$sender->sendMessage("§r§c§l(!) §r§cYou can't kick yourself!");
			return;
		}
		if(!$session->getFaction()->isMember($args["name"])) {
			$sender->sendMessage("§r§c§l(!) §r§cPlayer " . $args["name"] . " is not from your faction!");
			return;
		}
		$session->getFaction()->kick($args["name"]);
		$p = Server::getInstance()->getPlayerExact($args["name"]);
		if($p instanceof Player) {
			$p->sendMessage("§r§c§l(!) §r§cYou were kicked from your faction §6" . $session->getFaction()->getName() . "§c!");
		}
		$sender->sendMessage("§r§c§l(!) §r§cSuccessfully kicked " . $args["name"] . "!");
	}
}