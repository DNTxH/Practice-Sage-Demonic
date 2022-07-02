<?php

declare(strict_types=1);

namespace vale\sage\demonic\commands\types\sender;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\player\Player;
use pocketmine\Server;
use vale\sage\demonic\Loader;
use vale\sage\demonic\Utils;

class oListCommand extends Command
{
	public function execute(CommandSender $sender, string $aliasUsed, array $args): void
	{
		if (!$sender instanceof Player) {
			return;
		}
		$session = Loader::getInstance()->getSessionManager()->getSession($sender);
		$players = count(Server::getInstance()->getOnlinePlayers());
		$i = 0;
		$prefix = "§r§b§l--- Sage (Demonic) Player List ---";
		$factions = Loader::getInstance()->getFactionsManager()->getFactions();
		foreach ($factions as $faction) {
			if ($session->getFaction() !== $faction) {
				$i++;
			}
		}
			$prefix.= "\n §r§b§lPlayers: §r§a{$players} §r§7§l/ 85 §r§7players on your planet!";
			$factionsMessage = " §r§b§lFactions: §r§b$players §r§7players online in §r§b$i different factions.";
			$sender->sendMessage($prefix);
			$sender->sendMessage(Utils::sendRankPercentages());
			$sender->sendMessage($factionsMessage);
		}
	}
