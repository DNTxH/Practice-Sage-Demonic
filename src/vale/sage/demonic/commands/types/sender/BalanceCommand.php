<?php

declare(strict_types=1);

namespace vale\sage\demonic\commands\types\sender;

use CortexPE\Commando\args\BaseArgument;
use CortexPE\Commando\args\TestArg;
use CortexPE\Commando\BaseCommand;
use CortexPE\Commando\BaseSubCommand;
use pocketmine\command\CommandSender;
use pocketmine\player\Player;
use pocketmine\Server;
use pocketmine\utils\TextFormat;
use vale\sage\demonic\addons\types\envoys\Envoy;
use vale\sage\demonic\addons\types\tinkerer\TinkerInventory;
use vale\sage\demonic\addons\types\warp\WarpForm;
use vale\sage\demonic\Loader;

class BalanceCommand extends BaseCommand
{

	public function prepare(): void
	{
		$this->registerArgument(0, new TestArg("player", true));
	}

	public function onRun(CommandSender $sender, string $aliasUsed, array $args): void
	{
		if (!$sender instanceof Player) {
			return;
		}
		$session = Loader::getInstance()->getSessionManager()->getSession($sender);
		$balance = number_format($session->getBalance(), 2);
		if (!isset($args["player"])) {
			$sender->sendMessage("§r§e§lYour Balance: §r§f$" .$balance ."§r§7.00");
			$sender->sendMessage("§r§7Use /withdraw or /pay to transfer funds.");;
			return;
		}
		if (isset($args["player"])) {
			if (!$player = Server::getInstance()->getPlayerByPrefix($args["player"])) {
				$sender->sendMessage("§r§c§l(!) §r§c" .$args["player"]. " is not locally online.");
				return;
			}
			$newsession = Loader::getInstance()->getSessionManager()->getSession($player);
			$newbal = number_format($newsession->getBalance(),2);
			$sender->sendMessage("§r§e§l{$player->getName()} Balance: §r§f$" .$newbal ."§r§7.00");
			if($balance < $newbal){
				$sender->sendMessage("§r§7They're richer than you!");
			}
			if($balance > $newbal){
				$sender->sendMessage("§r§7You're richer than them!");
			}
			if($balance === $newbal){
				$sender->sendMessage("§r§7Your balances are equal!");
			}
		}
	}
}