<?php
namespace vale\sage\demonic\factions\command\subcommands;
use CortexPE\Commando\args\RawStringArgument;
use CortexPE\Commando\BaseSubCommand;
use pocketmine\player\Player;
use pocketmine\command\CommandSender;
use pocketmine\Server;
use vale\sage\demonic\factions\FactionManager;
use vale\sage\demonic\Loader;


class JoinSubCommand extends BaseSubCommand
{

	protected function prepare(): void
	{
		$this->registerArgument(0, new RawStringArgument("name",true));
	}

	public function onRun(CommandSender $sender, string $commandLabel, array $args): void {
		if(!isset($args["name"])){
			$sender->sendMessage("§r§c§l(!) §r§cInvalid usage, example: \n §r§b/f create §r§d<faction tag:string>");
			return;
		}
		$session = Loader::getInstance()->getSessionManager()->getSession($sender);
		if($session->getFaction() !== null) {
			$sender->sendMessage("§r§c§l(!) §r§cYou must leave your current faction first!");
			return;
		}
		$fac = Loader::getInstance()->getFactionsManager()->getFaction($args["name"]);
		
		if($fac == null) {
			$sender->sendMessage("§r§c§l(!) §r§cFaction not found");
			return;
		}
		if($fac->isBanned($sender->getName())) {
			$sender->sendMessage(Core::ERROR_PREFIX . "You need to be invited to join this faction");
			return;
		}
		if(!$fac->getAccess() and !$fac->isInvited($sender)) {
			$sender->sendMessage(Core::ERROR_PREFIX . "Faction not open/not invited.");
			return;
		} else {
			if(!Utils::hasFacRequested($fac, $sender)) {
				$sender->sendMessage(Loader::PERM_PREFIX . "You do not have any active requests from the following user.");
				return;
			}
			Utils::acceptFacReq($fac, $sender);
			$fac->addMember($sender->getName());
			$session->setFaction($fac);
			$fac->announce("§l§6" . $sender->getName() . "§a has just joined the faction!");
		}
	}
}