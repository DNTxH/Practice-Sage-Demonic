<?php
namespace vale\sage\demonic\factions\command\subcommands;
use CortexPE\Commando\args\RawStringArgument;
use CortexPE\Commando\BaseSubCommand;
use pocketmine\player\Player;
use pocketmine\command\CommandSender;
use pocketmine\Server;
use vale\sage\demonic\factions\FactionManager;
use vale\sage\demonic\Loader;


class CreateSubCommand extends BaseSubCommand
{

	protected function prepare(): void
	{
		$this->registerArgument(0, new RawStringArgument("name",true));
	}

	public function onRun(CommandSender $sender, string $commandLabel, array $args): void {
		if(!isset($args["name"])){
			$sender->sendMessage("§r§c§l(!) §r§cInvalid usage, example: \n §r§b/f create §r§d<faction tag:string>");
			return;
		}
		$session = Loader::getInstance()->getSessionManager()->getSession($sender);
		if($session->getFaction() !== null) {
			$sender->sendMessage("§r§c§l(!) §r§cYou must leave your current faction first!");
			return;
		}

		if(strlen($args["name"]) > 30) {
			$sender->sendMessage("§r§c§l(!) §r§cThe faction tag must be less than 30 characters.");
			return;
		}
		$faction = Loader::getInstance()->getFactionsManager()->getFaction($args["name"]);
		if($faction !== null) {
			$sender->sendMessage("§r§c§l(!) §r§cThat faction tag is already in use.");
			return;
		}
		$name = $args["name"];
		$leaderMessage = [
			"§r§6§l*** §r§e§lYOUR FACTION HAS BEEN CREATED §r§6§l***",
			"§r§6You are now the proud leader of §r§e'$name'",
			"§r§7§oUse /f help to view all Faction Commands (1-15)"
		];
		foreach ($leaderMessage as $line){
			$sender->sendMessage($line);
		}
		FactionManager::addFactionCreations(1);
		$count = Loader::getInstance()->getConfig()->get("faction_creations");
		$message = [
			"§r§e*§6* {$sender->getName()} §r§ehas founded the faction §r§f" . $args["name"] . " §r§7(§r§f#{$count}§r§7)",
			"§r§7(§oTIP: §r§o§7To create a team run /f create§r§7)"
		];
		foreach ($message as $ok){
			Server::getInstance()->broadcastMessage($ok);
		}
		Loader::getInstance()->getFactionsManager()->createFaction($args["name"], $sender);
	}
}