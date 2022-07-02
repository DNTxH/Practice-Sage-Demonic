<?php
namespace vale\sage\demonic\factions\command\subcommands;

use CortexPE\Commando\args\IntegerArgument;
use CortexPE\Commando\args\RawStringArgument;
use CortexPE\Commando\args\TestArg;
use CortexPE\Commando\BaseSubCommand;
use pocketmine\player\Player;
use pocketmine\command\CommandSender;
use pocketmine\Server;
use vale\sage\demonic\Loader;
use vale\sage\demonic\utils\Utils;

class DeinviteSubCommand extends BaseSubCommand
{

	protected function prepare(): void
	{
		$this->registerArgument(0, new RawStringArgument("name",true));
	}

	public function onRun(CommandSender $sender, string $aliasUsed, array $args): void
	{
		if (!$sender instanceof Player) return;
		if(!isset($args["name"])){
			$sender->sendMessage("§r§c§l(!) §r§cInvalid usage, example: \n §r§b/f deinvite §r§d<name:string>");
			return;
		}
		$session = Loader::getInstance()->getSessionManager()->getSession($sender);
		if($session->getFaction() == null) {
			$sender->sendMessage("§r§c§l(!) §r§cNo faction!");
			return;
		}
		$player = Loader::getInstance()->getServer()->getPlayerByPrefix($args[0]);
		if ($player == null) {
			$sender->sendMessage(Loader::PERM_PREFIX . "$args[0] cannot be found or is offline.");
			return;
		}
		$session2 = Loader::getInstance()->getSessionManager()->getSession($sender);
		if (!Utils::hasRequested($session2->getFaction(), $sender)) {
			$sender->sendMessage(Loader::PERM_PREFIX . "You do not have any active requests from the following user.");
			return;
		}
		Utils::declineFacReq($session2->getFaction(), $sender, true);
	}
}