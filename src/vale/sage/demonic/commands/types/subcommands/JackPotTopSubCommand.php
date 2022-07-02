<?php
namespace vale\sage\demonic\commands\types\subcommands;

use CortexPE\Commando\args\IntegerArgument;
use CortexPE\Commando\BaseSubCommand;
use pocketmine\player\Player;
use pocketmine\command\CommandSender;
use vale\sage\demonic\Loader;

class JackPotTopSubCommand extends BaseSubCommand
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
		$page = 1;
		if (isset($args["page"])) {
			$page = $args["page"];
		}
		if ((!is_numeric($page)) or $page < 1) {
			$page = 1;
		}
		$place = (($page - 1) * 5);
		$stmt = Loader::getInstance()->getMysqlProvider()->getDatabase()->prepare("SELECT username, jackpotwins, jackpotearnings FROM players ORDER BY jackpotwins DESC LIMIT 15 OFFSET " . $place);
		$stmt->execute();
		$stmt->bind_result($name, $wins, $earnings);
		++$place;
		$max = $page * 5;
		$text =  "§r§d§lTop Jackpot Winners (§b$page". "§b§l/§b$max" ."§d§l)";
		while ($stmt->fetch()) {
			$lol = $wins;
			$lol2 = number_format($earnings,2);
			$text .= "\n" . "§r§d§l$place. §r§f$name §r§d- §r§b§l$lol §r§bWins §l(§r§d$".$lol2. "§b§l)";
			$place++;
		}
		$stmt->close();
		$sender->sendMessage($text);
	}
}