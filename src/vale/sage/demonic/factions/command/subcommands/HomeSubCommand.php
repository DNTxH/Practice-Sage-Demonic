<?php
namespace vale\sage\demonic\factions\command\subcommands;

use CortexPE\Commando\args\IntegerArgument;
use CortexPE\Commando\args\TestArg;
use CortexPE\Commando\BaseSubCommand;
use pocketmine\player\Player;
use pocketmine\command\CommandSender;
use pocketmine\Server;
use pocketmine\utils\TextFormat;
use vale\sage\demonic\factions\FactionManager;
use vale\sage\demonic\Loader;


class HomeSubCommand extends BaseSubCommand
{

	protected function prepare(): void
	{
		$this->registerArgument(0, new IntegerArgument("page",true));
	}

	public function onRun(CommandSender $sender, string $aliasUsed, array $args): void
	{
		if (!$sender instanceof Player) {
			return;
		}
		$session = Loader::getInstance()->getSessionManager()->getSession($sender);
		if($session->getFaction() !== null) {
			$sender->sendMessage("§r§c§l(!) §r§cNo faction!");
			return;
		}
		$fac = $session->getFaction();
		
		if($fac->getHome() === "") {
			$sender->sendMessage(TextFormat::RED . "Your faction doesn't have a home set!");
			return;
		}
		$home = $fac->getHome();

		if(FactionManager::getInstance()->getFactionByChunk($home->x >> 4, $home->z >> 4) !== $fac->getId()) {
			$sender->sendMessage("§c§l(!) §r§cHome automatically deleted as your faction no longer owns that chunk");
			$fac->deleteHome();
			return;
		}
		$sender->sendMessage(TextFormat::GREEN . "Teleporting..");
	}
}