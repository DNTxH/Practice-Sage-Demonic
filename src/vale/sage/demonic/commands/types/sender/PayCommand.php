<?php

declare(strict_types=1);

namespace vale\sage\demonic\commands\types\sender;

use CortexPE\Commando\args\BaseArgument;
use CortexPE\Commando\args\IntegerArgument;
use CortexPE\Commando\args\TestArg;
use CortexPE\Commando\BaseCommand;
use CortexPE\Commando\BaseSubCommand;
use pocketmine\command\CommandSender;
use pocketmine\player\Player;
use pocketmine\Server;
use pocketmine\utils\TextFormat;
use vale\sage\demonic\Loader;

class PayCommand extends BaseCommand
{

	public function prepare(): void
	{
		$this->registerArgument(0, new TestArg("player", true));
		$this->registerArgument(1, new IntegerArgument("amount", true));
	}

	public function onRun(CommandSender $sender, string $aliasUsed, array $args): void
	{
		if (!$sender instanceof Player) {
			return;
		}
		$session = Loader::getInstance()->getSessionManager()->getSession($sender);
		$balance = number_format($session->getBalance(), 2);
		if (!isset($args["player"])) {
			$sender->sendMessage("§r§c/pay <player> <amount>");
			return;
		}
		if(!isset($args["amount"])){
			$sender->sendMessage("§r§c/pay <player> <amount>");
			return;
		}
		if (isset($args["player"])) {
			if (!$player = Server::getInstance()->getPlayerByPrefix($args["player"])) {
				$sender->sendMessage("§r§c§l(!) No online player by the name '" . $args["$player"] . " could be found!");
				return;
			}
			$newSession = Loader::getInstance()->getSessionManager()->getSession($player);
			$check = false;
			if($balance < $args["amount"]){
				$sender->sendMessage("§r§c§l(!) §r§cYou don't have sufficient funds!");
				$check = true;
				return;
			}
			if($args["amount"] > 100000000000 && $check === true){
				$sender->sendMessage("§r§cbalance must be >= 0, <= 100000000000 got 1.0E+17");
				return;
			}
			$session->setBalance($session->getBalance() - $args["amount"]);
			$newSession->addBalance($args["amount"]);
			$formatted = number_format($args["amount"],2);
			$session->getPlayer()->sendMessage("§r§aYou paid $$formatted to {$newSession->getPlayer()->getScoreTag()}!");
			$newSession->getPlayer()->sendMessage("§r§aYou recieved $$formatted from {$session->getPlayer()->getScoreTag()}!");
		}
	}
}