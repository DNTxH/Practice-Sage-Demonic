<?php
namespace vale\sage\demonic\factions\command\subcommands;

use CortexPE\Commando\args\IntegerArgument;
use CortexPE\Commando\args\TestArg;
use CortexPE\Commando\BaseSubCommand;
use pocketmine\player\Player;
use pocketmine\command\CommandSender;
use pocketmine\Server;
use vale\sage\demonic\Loader;


class ChatSubCommand extends BaseSubCommand
{
	public static $factionChat = [

	];

	public static $allyChat = [

	];

	protected function prepare(): void
	{
		$this->registerArgument(0, new IntegerArgument("<type>",true));
	}

	public function onRun(CommandSender $sender, string $aliasUsed, array $args): void
	{
		if (!$sender instanceof Player) {
			return;
		}
		$session = Loader::getInstance()->getSessionManager()->getSession($sender);
		if($session->getFaction() === null) {
			$sender->sendMessage("§r§c§l(!) §r§cYou do not have a Faction!");
			return;
		}
		switch($args["type"]) {
			case "f":
			case "faction":
				if(in_array($sender->getName(), self::$allyChat)) {
					unset(self::$allyChat[array_search($sender->getName(), self::$allyChat)]);
				}
				if(!in_array($sender->getName(), self::$factionChat)) {
					self::$factionChat[] = $sender->getName();
				}
				$sender->sendMessage("§r§c§l(!) §r§aChat mode: §6faction");
			break;
			case "a":
			case "ally":
				if(in_array($sender->getName(), self::$factionChat)) {
					unset(self::$factionChat[array_search($sender->getName(), self::$fchat)]);
				}
				if(!in_array($sender->getName(), self::$allyChat)) {
					self::$allyChat[] = $sender->getName();
				}
			$sender->sendMessage("§r§c§l(!) §r§aChat mode: §dally");
			break;
			case "p":
			case "public":
				if(in_array($sender->getName(), self::$factionChat)) {
					unset(self::$factionChat[array_search($sender->getName(), self::$factionChat)]);
				}
				if(in_array($sender->getName(), self::$allyChat)) {
					unset(self::$allyChat[array_search($sender->getName(), self::$allyChat)]);
				}
				$sender->sendMessage("§r§c§l(!) §r§aChat mode: §epublic");
			break;
		}
	}
}