<?php
namespace vale\sage\demonic\commands\types\subcommands;

use CortexPE\Commando\args\IntegerArgument;
use CortexPE\Commando\args\TestArg;
use CortexPE\Commando\BaseSubCommand;
use pocketmine\player\Player;
use pocketmine\command\CommandSender;
use pocketmine\Server;
use vale\sage\demonic\addons\types\envoys\events\JackPotEvent;
use vale\sage\demonic\Loader;

class JackPotStatsSubCommand extends BaseSubCommand
{

	protected function prepare(): void
	{
		$this->registerArgument(0, new TestArg("player",true));
	}


	public function onRun(CommandSender $sender, string $aliasUsed, array $args): void
	{
		if (!$sender instanceof Player) {
			return;
		}
		if (!isset($args["player"])) {
			JackPotEvent::getInstance()->formatStats($sender);
			return;
		}
		if (isset($args["player"])) {
			if (!$player = Server::getInstance()->getPlayerByPrefix($args["player"])) {
				$sender->sendMessage("§r§c§l(!) §r§c" . $args["player"] . " is not locally online.");
				return;
			}
			$session = Loader::getInstance()->getSessionManager()->getSession($player);
			$mytickets = JackPotEvent::getInstance()->getTickets($player->getName());
			$wins = $session->getJackPotWins();
			$sender->sendMessage("§r§d§lSage Jackpot Stats §r§7({$player->getName()})");
			$sender->sendMessage("§r§bTotal Winnings: §r§d§l$" . "§r§d". number_format($session->getJackPotEarnings(),2));
			$sender->sendMessage("§r§b§lTotal Tickets Purchased: §r§d$mytickets");
			$sender->sendMessage("§r§b§lTotal Jackpot Wins: §r§d$wins");
		}
	}
}