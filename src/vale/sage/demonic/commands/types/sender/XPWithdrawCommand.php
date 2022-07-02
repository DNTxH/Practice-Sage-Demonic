<?php

declare(strict_types=1);

namespace vale\sage\demonic\commands\types\sender;

use CortexPE\Commando\args\FloatArgument;
use CortexPE\Commando\args\IntegerArgument;
use CortexPE\Commando\BaseCommand;
use pocketmine\command\CommandSender;
use pocketmine\player\Player;
use vale\sage\demonic\commands\types\subcommands\XPAllSubCommand;
use vale\sage\demonic\Loader;
use vale\sage\demonic\rewards\Rewards;

class XPWithdrawCommand extends BaseCommand
{

	public function prepare(): void
	{
		$this->registerArgument(0, new FloatArgument("amount"));
		$this->registerSubCommand(new XPAllSubCommand("all"));
	}

	/** @var array $cooldown */
	public static array $cooldown = [];

	public function onRun(CommandSender $sender, string $aliasUsed, array $args): void
	{
		if (!$sender instanceof Player) {
			return;
		}
		$session = Loader::getInstance()->getSessionManager()->getSession($sender);
		if (!isset($args["amount"])) {
			$sender->sendMessage("§r§c/xpbottle <amount>");
			return;
		}
		if (isset(self::$cooldown[$sender->getName()]) && microtime(true) - self::$cooldown[$sender->getName()] <= 90) {
			$delayMessage = round(90 - abs(self::$cooldown[$sender->getName()] - microtime(true)), 2);
			$sender->sendMessage("§r§c§l(!) §r§cYou cannot create another XP Bottle for {$delayMessage} seconds(s).");
			$sender->sendMessage("§r§7Complete a Rank Quest from " . Loader::BUYCRAFT . " to decrease this delay.");
			return;
		}
		if ($args["amount"] < 1) {
			$sender->sendMessage("§r§camount must be > 0 got '" . $args["amount"] . "'");
			return;
		}
		if($args["amount"] > $session->getPlayer()->getXpManager()->getCurrentTotalXp()){
			$sender->sendMessage("§r§c§l(!) §r§cYou don't have the sufficient xp!");
			return;
		}
		$sender->sendMessage("§r§c§l-". $args["amount"] . " xp");
		$note = Rewards::createXPBottle($sender,$args["amount"]);
		$sender->getInventory()->addItem($note);
		self::$cooldown[$sender->getName()] = microtime(true);
		$sender->sendMessage("§r§eYou are now afflicted with EXP Exhaustion for 90 seconds(s).");
		$sender->sendMessage("§r§eWhile XP exhausted you may not (Teleport, Warp, and or Withdraw XP) for 90 second(s).");
	}
}