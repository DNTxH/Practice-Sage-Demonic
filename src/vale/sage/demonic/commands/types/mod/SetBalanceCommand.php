<?php

declare(strict_types=1);

namespace vale\sage\demonic\commands\types\mod;

use CortexPE\Commando\args\BaseArgument;
use CortexPE\Commando\args\IntegerArgument;
use CortexPE\Commando\args\TestArg;
use CortexPE\Commando\BaseCommand;
use CortexPE\Commando\BaseSubCommand;
use pocketmine\command\CommandSender;
use pocketmine\network\mcpe\protocol\LevelSoundEventPacket;
use pocketmine\network\mcpe\protocol\PlaySoundPacket;
use pocketmine\player\Player;
use pocketmine\Server;
use pocketmine\utils\TextFormat;
use vale\sage\demonic\commands\types\subcommands\RankListSubCommand;
use vale\sage\demonic\commands\types\subcommands\ReclaimResetSubCommand;
use vale\sage\demonic\Loader;

class SetBalanceCommand extends BaseCommand
{

	public int $bal;

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
			$sender->sendMessage("§r§c/setbalance <player> <amount> ");
			$sender->sendMessage("§r§7Run /setbalance to view all the available players.");
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
			$this->bal = $session->getBalance();
			$oldbal =  $this->bal;
			$sender->sendMessage("§r§aYou have successfully set {$player->getName()}'s Balance to ". $args["amount"] . "$");
			$session->setBalance($args["amount"]);
			$session->getPlayer()->sendMessage("§r§a§l(!) §r§aYour balanace was updated by a moderator");
			$session->getPlayer()->sendMessage("§r§7Here are the following changes to your session.");
			$session->getPlayer()->sendMessage("§r§c§lOLD: §r§f$". number_format($oldbal,2));
			$session->getPlayer()->sendMessage("§r§a§lNEW: §r§f$". number_format($session->getBalance(),2));
			$session->getPlayer()->sendMessage("§r§7If you are not comfortable with these changes, please make a ticket via discord.");
			Loader::playSound($player, "random.levelup", 2);
		}
	}
}