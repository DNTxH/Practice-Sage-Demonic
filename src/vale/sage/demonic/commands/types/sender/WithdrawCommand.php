<?php

declare(strict_types=1);

namespace vale\sage\demonic\commands\types\sender;

use CortexPE\Commando\args\BaseArgument;
use CortexPE\Commando\args\IntegerArgument;
use CortexPE\Commando\args\RawStringArgument;
use CortexPE\Commando\args\TestArg;
use CortexPE\Commando\BaseCommand;
use CortexPE\Commando\BaseSubCommand;
use pocketmine\command\CommandSender;
use pocketmine\player\Player;
use pocketmine\Server;
use pocketmine\utils\TextFormat;
use vale\sage\demonic\commands\types\subcommands\WithdrawAllSubCommand;
use vale\sage\demonic\Loader;
use vale\sage\demonic\rewards\Rewards;

class WithdrawCommand extends BaseCommand
{

	public function prepare(): void
	{
		$this->registerArgument(0, new IntegerArgument("amount"));
		$this->registerSubCommand(new WithdrawAllSubCommand("all"));
	}

	public function onRun(CommandSender $sender, string $aliasUsed, array $args): void
	{
		if (!$sender instanceof Player) {
			return;
		}
		$session = Loader::getInstance()->getSessionManager()->getSession($sender);
		if (!isset($args["amount"])) {
			$sender->sendMessage("§r§c/withdraw <amount>");
			return;
		}
		if ($args["amount"] < 1) {
			$sender->sendMessage("§r§camount must be > 0 got '" . $args["amount"] . "'");
			return;
		}
		if($args["amount"] > $session->getBalance()){
			$sender->sendMessage("§r§c§l(!) §r§cYou don't have sufficient funds!");
			return;
		}
		$formmated = number_format($args["amount"],2);
		$sender->sendMessage("§r§aYou have signed a sage note worth $$formmated!");
		$note = Rewards::createMoneyNote($sender,$args["amount"]);
		$sender->getInventory()->addItem($note);
	}
}