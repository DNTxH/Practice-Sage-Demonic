<?php
namespace vale\sage\demonic\commands\types\mod;

use CortexPE\Commando\args\BaseArgument;
use CortexPE\Commando\BaseCommand;
use pocketmine\command\CommandSender;
use pocketmine\player\Player;
use vale\sage\demonic\commands\types\subcommands\CustomEnchantsListSubCommand;

class CustomEnchantCommand extends BaseCommand{

	protected function prepare(): void
	{
		$this->registerSubCommand(new CustomEnchantsListSubCommand("list","view all customenchants"));
	}

	public function onRun(CommandSender $sender, string $aliasUsed, array $args): void
	{
		if(!$sender instanceof Player) return;
		$sender->sendMessage($this->formatMessage());
	}

	public function formatMessage(): string{
		return "§r§d§lCustomEnchants §r§b§lHelp §r§7(1-5) \n §r§d§l* §r§b/ce list §r§f- §r§dview all the avaliable custom enchants.";
	}
}