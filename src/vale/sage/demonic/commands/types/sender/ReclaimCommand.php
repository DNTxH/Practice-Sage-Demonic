<?php

namespace vale\sage\demonic\commands\types\sender;

use CortexPE\Commando\args\RawStringArgument;
use CortexPE\Commando\BaseCommand;
use pocketmine\command\CommandSender;
use pocketmine\player\Player;
use pocketmine\Server;
use vale\sage\demonic\commands\types\subcommands\ReclaimResetSubCommand;
use vale\sage\demonic\Loader;
use vale\sage\demonic\ranks\rank\RankIDS;
use vale\sage\demonic\ranks\RankManager;

class ReclaimCommand extends BaseCommand{

	protected function prepare(): void
	{
		$this->registerSubCommand(new ReclaimResetSubCommand("reset"));
	}

	public function onRun(CommandSender $sender, string $aliasUsed, array $args): void
	{
		if(!$sender instanceof Player) return;
		$session = Loader::getInstance()->getSessionManager()->getSession($sender);
		if($session->getReclaim() === 1){
			$session->getPlayer()->sendMessage("§r§c§l(!) §r§cYou have already claimed your pending packages.");
			Loader::playSound($sender, "block.scaffolding.step");
			return;
		}
		switch ($session->getRank()->getIdentifier()){
			case RankIDS::BASIC:
				$formatted = Loader::getInstance()->getRankManager()->formatNameTag($session);
				Server::getInstance()->broadcastMessage("$formatted §r§7has §r§f/reclaimed §r§7their §r§fpackages.");
				$sender->sendMessage("§r§e§l(!) §r§eYou have successfully redeemed your §r§f§l<§r§7Trainee§r§f§l> §r§epackages.");
				$session->setReclaimed();
				Loader::playSound($sender,"firework.launch");
				break;
			case RankIDS::ADMIN:
				$formatted = Loader::getInstance()->getRankManager()->formatNameTag($session);
				Server::getInstance()->broadcastMessage("$formatted §r§7has §r§f/reclaimed §r§7their §r§fpackages.");
				$sender->sendMessage("§r§e§l(!) §r§eYou have successfully redeemed your §r§f§l<§r§4Administrator§r§f§l> §r§epackages.");
				$session->setReclaimed();
				Loader::playSound($sender,"firework.launch");
				break;
		}
	}
}