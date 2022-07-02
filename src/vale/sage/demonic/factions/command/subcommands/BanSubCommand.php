<?php
namespace vale\sage\demonic\factions\command\subcommands;
use CortexPE\Commando\args\RawStringArgument;
use CortexPE\Commando\BaseSubCommand;
use pocketmine\player\Player;
use pocketmine\command\CommandSender;
use pocketmine\Server;
use vale\sage\demonic\factions\FactionManager;
use vale\sage\demonic\Loader;


class BanSubCommand extends BaseSubCommand
{

	protected function prepare(): void
	{
		$this->registerArgument(0, new RawStringArgument("name",true));
	}

	public function onRun(CommandSender $sender, string $commandLabel, array $args): void {
		if(!$sender instanceof Player) {
			return;
		}
		if(!isset($args["name"])){
			$sender->sendMessage("§r§c§l(!) §r§cInvalid usage, example: \n §r§b/f ban §r§d<name:string>");
			return;
		}
		$session = Loader::getInstance()->getSessionManager()->getSession($sender);
		if($session->getFaction() == null) {
			$sender->sendMessage("§r§c§l(!) §r§cNo faction!");
			return;
		}
		if($fac->getLeader() === $args["name"]) {
			$sender->sendMessage("§cYou can't ban yourself in your own faction!");
			return;
		}
		if($fac->isBanned($args[0])) {
			$sender->sendMessage("§cPlayer $args[0] is already banned from your faction!");
		}
		$fac->setBanned($args[0], true);

		if($player instanceof Player) {
			$player->sendMessage("§fYou were banned from the faction §6" . $fac->getName() . "§c!");
		}
		$sender->sendMessage("§fSuccessfully banned " . $args[0] . "!");
	}
}