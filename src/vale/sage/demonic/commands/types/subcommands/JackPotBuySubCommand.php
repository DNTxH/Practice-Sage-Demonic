<?php
namespace vale\sage\demonic\commands\types\subcommands;

use CortexPE\Commando\args\IntegerArgument;
use CortexPE\Commando\BaseSubCommand;
use pocketmine\player\Player;
use pocketmine\command\CommandSender;
use vale\sage\demonic\addons\types\warp\types\JackpotForm;

class JackPotBuySubCommand extends BaseSubCommand
{

	protected function prepare(): void
	{
		$this->registerArgument(0,new IntegerArgument("amount",true));
	}


	public function onRun(CommandSender $sender, string $aliasUsed, array $args): void
	{
		if (!$sender instanceof Player) {
			return;
		}
		if(!isset($args["amount"])){
			$form = new JackpotForm($sender,1000, 1);
			$sender->sendForm($form);
			return;
		}
		if($args["amount"] <= 0){
			$sender->sendMessage("§r§c§l(!) §r§cThe amount must be greater then 0.");
			return;
		}
		$argument = $args["amount"] !== null ? $args["amount"] : 1;
		$form = new JackpotForm($sender,$args["amount"] * 1000, $args["amount"]);
		$sender->sendForm($form);
	}
}