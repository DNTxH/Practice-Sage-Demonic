<?php

declare(strict_types=1);

namespace vale\sage\demonic\commands\types\mod;

use CortexPE\Commando\args\IntegerArgument;
use CortexPE\Commando\args\TestArg;
use CortexPE\Commando\BaseCommand;
use pocketmine\command\CommandSender;
use pocketmine\player\Player;
use pocketmine\Server;
use vale\sage\demonic\Loader;

class ClearCommand extends BaseCommand
{

	/**
	 * @throws \CortexPE\Commando\exception\ArgumentOrderException
	 */
	public function prepare(): void
	{
		$this->registerArgument(0, new TestArg("player", true));
	}

	public function onRun(CommandSender $sender, string $aliasUsed, array $args): void
	{
		if (!$sender instanceof Player) {
			return;
		}

		if(!Loader::getInstance()->getServer()->isOp($sender->getName())){
			$sender->sendMessage("§r§c§l(!) §r§cThis command is only available to moderators.");
			$sender->sendMessage("§r§7Running this command again will flag your account for trying to exploit.");
			return;
		}
		if (!isset($args["player"])) {
		     $count = count	($sender->getInventory()->getContents());
			$sender->sendMessage("§r§6§lRemoving §r§c$count §r§6Item(s), from your Inventory.");
			$sender->getInventory()->clearAll();
			return;
		}

		if (isset($args["player"])) {
			if (!$player = Server::getInstance()->getPlayerByPrefix($args["player"])) {
				$sender->sendMessage("§r§c§l(!) §r§c" . $args["player"] . " is not locally online.");
				return;
			}
			$count = count	($player->getInventory()->getContents());
			$sender->sendMessage("§r§6§lRemoving §r§c$count §r§6Item(s), from {$player->getName()}'s Inventory.");
			$player->getInventory()->clearAll();
		}
	}
}