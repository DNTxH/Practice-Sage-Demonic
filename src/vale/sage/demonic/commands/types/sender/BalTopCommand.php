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
use vale\sage\demonic\commands\types\subcommands\TopMoneyOnlineSubCommand;
use vale\sage\demonic\Loader;

class BalTopCommand extends BaseCommand
{

	public function prepare(): void
	{
		$this->registerArgument(0, new IntegerArgument("page", true));
		$this->registerSubCommand(new TopMoneyOnlineSubCommand("online"));
	}

	public function onRun(CommandSender $sender, string $aliasUsed, array $args): void
	{
		if (!$sender instanceof Player) {
			return;
		}
		$session = Loader::getInstance()->getSessionManager()->getSession($sender);
		$balance = number_format($session->getBalance(), 2);
		$page = 1;
		if (isset($args["page"])) {
			$page = $args["page"];
		}
		if ((!is_numeric($page)) or $page < 1) {
			$page = 1;
		}
		$place = (($page - 1) * 5);
		$stmt = Loader::getInstance()->getMysqlProvider()->getDatabase()->prepare("SELECT username, balance FROM players ORDER BY balance DESC LIMIT 10 OFFSET " . $place);
		$stmt->execute();
		$stmt->bind_result($name, $balance);
		++$place;
		$max = $page * 5;
		$text =  "§r§e§lTop Balances (§a$page". "§e§l/§a$max" ."§e§l)";
		while ($stmt->fetch()) {
			$lol = number_format((float)$balance,2);
			$text .= "\n" . "§r§a§l$place. §r§e$name: §r§a§l$$lol";
			$place++;
		}
		$stmt->close();
		$sender->sendMessage($text);
	}
}