<?php
namespace vale\sage\demonic\factions\command\subcommands;

use CortexPE\Commando\args\IntegerArgument;
use CortexPE\Commando\args\TestArg;
use CortexPE\Commando\BaseSubCommand;
use pocketmine\player\Player;
use pocketmine\command\CommandSender;
use pocketmine\Server;
use vale\sage\demonic\Loader;


class SetWarpSubCommand extends BaseSubCommand
{

	protected function prepare(): void
	{
		$this->registerArgument(0, new IntegerArgument("page",true));
	}

	public function onRun(CommandSender $sender, string $aliasUsed, array $args): void
	{
		if(!$sender instanceof Player) {
			return;
		}
		if(!isset($args["name"])){
			$sender->sendMessage("§r§c§l(!) §r§cInvalid usage, example: \n §r§b/f warp §r§d<name:string>");
			return;
		}
		$session = Loader::getInstance()->getSessionManager()->getSession($sender);
		if($session->getFaction() == null) {
			$sender->sendMessage("§r§c§l(!) §r§cNo faction!");
			return;
		}
		if(!is_null($fac->getWarp($args["name"]))) {
			$sender->sendMessage("§cFac Warp exists!");
			return;
		} else {
			$fac->addWarp($sender->getLocation(), $args["name"]);
			$sender->sendMessage("§cAdded faction warp!");
		}
	}
}