<?php

declare(strict_types=1);

namespace vale\sage\demonic\commands\types\mod;

use CortexPE\Commando\args\BaseArgument;
use CortexPE\Commando\args\RawStringArgument;
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

class SetRankCommand extends BaseCommand
{

	public function prepare(): void
	{
		$this->registerArgument(0, new TestArg("player", true));
		$this->registerSubCommand(new RankListSubCommand("list"));
		$this->registerArgument(1, new RawStringArgument("rank",true));
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
			$sender->sendMessage("§r§c/setrank <player> <rank> ");
			$sender->sendMessage("§r§7Run /setrank list to view all the available ranks.");
			return;
		}

		if(!isset($args["rank"])){
			$sender->sendMessage("§r§cYou must provide a Rank.");
			$sender->sendMessage("§r§7To view all the available ranks type the sub-command setrank list.");
			return;
		}
			if(!in_array($args["rank"], Loader::getInstance()->getRankManager()->getAll())){
				$sender->sendMessage("§r§cThe Rank ". $args["rank"] . " could not be found.");
				$sender->sendMessage("§r§7To view all the available ranks type the sub-command setrank list.");
				return;
			}

		if (isset($args["player"])) {
			if (!$player = Server::getInstance()->getPlayerByPrefix($args["player"])) {
				$sender->sendMessage("§r§c§l(!) §r§c" . $args["player"] . " is not locally online.");
				return;
			}
			$session = Loader::getInstance()->getSessionManager()->getSession($player);
			$rank = Loader::getInstance()->getRankManager()->get($args["rank"]);
			$session->setRank($rank);
			$sender->sendMessage("§r§aThe Rank Transaction was Successful.");
			$player->sendMessage("§r§b§l(!) You've UNLOCKED the §r§d§l{$args["rank"]} §r§b§lRank!");
			$player->sendMessage("§r§7Congratulations! Enjoy the perks on your new rank!");
			Loader::playSound($player,"firework.large_blast",2);
			Loader::playSound($player,"firework.shoot",2);

		}
	}
}