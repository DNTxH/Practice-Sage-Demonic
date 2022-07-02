<?php
namespace vale\sage\demonic\factions\command\subcommands;

use CortexPE\Commando\args\IntegerArgument;
use CortexPE\Commando\args\RawStringArgument;
use CortexPE\Commando\BaseSubCommand;
use pocketmine\player\Player;
use pocketmine\command\CommandSender;
use vale\sage\demonic\Loader;

class ShowClaimsSubCommand extends BaseSubCommand
{

	protected function prepare(): void
	{
	}

	public function onRun(CommandSender $sender, string $aliasUsed, array $args): void
	{
		if (!$sender instanceof Player) {
			return;
		}
		$session = Loader::getInstance()->getSessionManager()->getSession($sender);

		if($session->getFaction() == null) {
			$sender->sendMessage("§r§c§l(!) §r§cNo faction!");
			return;
		}
		$all = $session->getFaction()->getFactionChunks($sender)->getId();

		$m = "---Claims---\n";

		foreach($all as $hash => $id) {
            World::getXZ($hash, $x, $z);
			$m .= "§7 - §f({$x}, {$z})\n";
		}
		$sender->sendMessage($m);
	}
}