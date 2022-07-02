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

class SetSoulsCommand extends BaseCommand
{

	/**
	 * @throws \CortexPE\Commando\exception\ArgumentOrderException
	 */
	public function prepare(): void
	{
		$this->registerArgument(0, new TestArg("player", true));
		$this->registerArgument(1, new IntegerArgument("amount",true));
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
			$sender->sendMessage("§r§c/setsouls <player> <amount> ");
			$sender->sendMessage("§r§7Run /setsouls to view all the available players.");
			return;
		}

		if(!isset($args["amount"])){
			$sender->sendMessage("§r§cYou must provide a amount");
			$sender->sendMessage("§r§7Ensure that the amount is a integer.");
			return;
		}

		if (isset($args["player"])) {
			if (!$player = Server::getInstance()->getPlayerByPrefix($args["player"])) {
				$sender->sendMessage("§r§c§l(!) §r§c" . $args["player"] . " is not locally online.");
				return;
			}
			$session = Loader::getInstance()->getSessionManager()->getSession($player);
			$sender->sendMessage("§r§aYou have successfully set {$player->getName()}'s Souls to ". $args["amount"]);
			$session->setSouls($args["amount"]);
			$session->getPlayer()->sendMessage("§r§c§l+ ". $args["amount"] . " Soul(s)");
			Loader::playSound($player, "random.levelup", 2);
		}
	}
}