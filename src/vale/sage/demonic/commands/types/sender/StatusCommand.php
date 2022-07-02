<?php

declare(strict_types=1);

namespace vale\sage\demonic\commands\types\sender;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\player\Player;
use pocketmine\Server;
use vale\sage\demonic\Loader;

class StatusCommand extends Command
{

	public function execute(CommandSender $sender, string $aliasUsed, array $args): void
	{
		if ($sender instanceof Player) {
			$last = Loader::secondsToTime(time() - Loader::getInstance()->getConfig()->get("uptime"));
			$tps = Server::getInstance()->getTicksPerSecondAverage();
			$ping = (int)$sender->getNetworkSession()->getPing();
			$message = "§r§b§lSage Server Status \n §r§b§lUptime: §r§d$last \n §r§b§lServer 'TPS' = §r§a$tps  \n §r§b§lYour Ping: §r§a{$ping}ms";
			$sender->sendMessage($message);
		}
	}
}