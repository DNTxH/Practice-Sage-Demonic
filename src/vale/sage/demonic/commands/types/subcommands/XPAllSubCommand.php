<?php
namespace vale\sage\demonic\commands\types\subcommands;

use CortexPE\Commando\BaseSubCommand;
use pocketmine\player\Player;
use pocketmine\command\CommandSender;
use vale\sage\demonic\Loader;
use vale\sage\demonic\rewards\Rewards;

class XPAllSubCommand extends BaseSubCommand{

	protected function prepare(): void
	{
	}

	public function onRun(CommandSender $sender, string $aliasUsed, array $args): void
	{
		if(!$sender instanceof Player){
			return;
		}
		$session = Loader::getInstance()->getSessionManager()->getSession($sender);
		if($session->getPlayer()->getXpManager()->getCurrentTotalXp() <= 0){
			$sender->sendMessage("§r§c§l(!) §r§cYou don't have the sufficient xp!");
			return;
		}
		$total = $sender->getXpManager()->getCurrentTotalXp();
		$sender->sendMessage("§r§c§l- ". $total . " xp");
		$note = Rewards::createXPBottle($sender,$total,1,true);
		$sender->getInventory()->addItem($note);
	}
}