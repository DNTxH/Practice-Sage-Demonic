<?php
namespace vale\sage\demonic\factions\command\subcommands;

use CortexPE\Commando\args\IntegerArgument;
use CortexPE\Commando\args\RawStringArgument;
use CortexPE\Commando\BaseSubCommand;
use pocketmine\player\Player;
use pocketmine\command\CommandSender;
use vale\sage\demonic\factions\FactionManager;
use vale\sage\demonic\Loader;

class TopSubCommand extends BaseSubCommand
{

	protected function prepare(): void
	{
		$this->registerArgument(0, new RawStringArgument("page",true));
	}

	public function onRun(CommandSender $sender, string $aliasUsed, array $args): void
	{
		if(!isset($args["name"])){
			$sender->sendMessage("§r§c§l(!) §r§cInvalid usage, example: \n §r§b/f who | info §r§d<faction tag:string>");
			return;
		}
		$offset = ((int) ($args[0] ?? 1) - 1) * 10;
		
		if($offset > 90) $offset = 90;
		//todo
		$db = Loader::getInstance()->getMysqlProvider()->getDatabase();
		
		$st = $db->query("SELECT * FROM faction;");
		/*$r = [];
				
		foreach($factions->fetchArray(SQLITE3_ASSOC) as $fac) {
			var_dump($fac["id"]);
					
			$r[$fac["name"]] = FactionManager::getInstance()->getChunkValue($fac["id"]);
		}
		sort($r);
		$i = 0;
		while($r = 10) {
			$msg .= (++$i) . ". " . $r[0] . ", $" . number_format(FactionManager::getInstance()->getChunkValue($r[1])) . "\n";
		}*/
		$msg = "§6 --- Faction Top Value ---";
		$i = 0;
				
		while($r = $st->fetch_array(SQLITE3_ASSOC)) {
			sort($r);
					
			if(FactionManager::getInstance()->getFaction($r[2]) !== null) {
				$msg .= (++$i) . ". " . $r[2] . ", $" . number_format(FactionManager::getInstance()->getChunkValue(FactionManager::getInstance()->getFaction($r[2])->getId())) . "\n";
			}
		}
				
		if(($next = $offset / 10) < 9) $msg .= "§7Type §d/f top value " . ($next + 2) . "§7 to read the next page.";
		$sender->sendMessage($msg);
		return;
	}
}