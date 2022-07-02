<?php
namespace vale\sage\demonic\commands\types\subcommands;

use CortexPE\Commando\BaseSubCommand;
use pocketmine\player\Player;
use pocketmine\command\CommandSender;
use vale\sage\demonic\Loader;
use vale\sage\demonic\rewards\Rewards;

class WithdrawAllSubCommand extends BaseSubCommand{

	protected function prepare(): void
	{
		// TODO: Implement prepare() method.
	}

	public function onRun(CommandSender $sender, string $aliasUsed, array $args): void
	{
      if(!$sender instanceof Player){
		  return;
	  }
	    $session = Loader::getInstance()->getSessionManager()->getSession($sender);
	     if($session->getBalance() < 1){
		  $sender->sendMessage("§r§c§l(!) §r§cYou don't have sufficient funds!");
		  return;
	  }
		$formmated = number_format($session->getBalance(),2);
		$sender->sendMessage("§r§aYou have signed a sage note worth $$formmated!");
		$note = Rewards::createMoneyNote($sender,$session->getBalance());
		$sender->getInventory()->addItem($note);
	}
}