<?php
namespace vale\sage\demonic\factions\command\subcommands;

use CortexPE\Commando\args\RawStringArgument;
use CortexPE\Commando\args\TestArg;
use CortexPE\Commando\BaseSubCommand;
use pocketmine\player\Player;
use pocketmine\command\CommandSender;
use pocketmine\Server;
use vale\sage\demonic\Loader;
use pocketmine\utils\TextFormat;

class NotificationsSubCommand extends BaseSubCommand
{

	protected function prepare(): void
	{
		$this->registerArgument(0, new RawStringArgument("desc",true));
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
		$d = TextFormat::clean(implode(" ", $args[]));

		if(strlen($d) > 150){
			$sender->sendMessage("§r§c§l(!) §r§cDscription cannot be longer than 150 characters!");
			return;
		}
		$session->getFaction()->setDescription($d);
		$sender->sendMessage("§r§c§l(!) §r§cSuccessfully set description to " . $d . "§a!");
	}
}