<?php
namespace vale\sage\demonic\commands\types\subcommands;

use CortexPE\Commando\args\TestArg;
use CortexPE\Commando\BaseSubCommand;
use pocketmine\player\Player;
use pocketmine\command\CommandSender;
use pocketmine\Server;
use vale\sage\demonic\Loader;


class ReclaimResetSubCommand extends BaseSubCommand
{

	protected function prepare(): void
	{
		$this->registerArgument(0, new TestArg("player"));
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
			$sender->sendMessage("ARGS PLAYER NOT DEFINED");
			return;
		}
		if (isset($args["player"])) {
			if (!$player = Server::getInstance()->getPlayerByPrefix($args["player"])) {
				$sender->sendMessage("§r§c§l(!) §r§c" . $args["player"] . " is not locally online.");
				return;
			}
			$session = Loader::getInstance()->getSessionManager()->getSession($player);
			$session->setReclaimed(0);
			$status = $session->getReclaim() === 0 ? "Unclaimed" : "Claimed";
			$formattedoperator = Loader::getInstance()->getRankManager()->formatNameTag(Loader::getInstance()->getSessionManager()->getSession($sender));
			$formatted = Loader::getInstance()->getRankManager()->formatNameTag($session);
			$sender->sendMessage("$formatted's §r§aReclaim Status has been updated to $status.");
			$session->getPlayer()->sendMessage("§r§a§l(!) §r§aThe operator $formattedoperator §r§ahas updated your reclaim status to: $status");
		}
	}
}