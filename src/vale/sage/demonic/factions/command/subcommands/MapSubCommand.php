<?php
namespace vale\sage\demonic\factions\command\subcommands;

use CortexPE\Commando\args\IntegerArgument;
use CortexPE\Commando\args\RawStringArgument;
use CortexPE\Commando\BaseSubCommand;
use pocketmine\player\Player;
use pocketmine\command\CommandSender;
use vale\sage\demonic\factions\FactionManager;
use vale\sage\demonic\Loader;


class MapSubCommand extends BaseSubCommand
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

		if ($session->getFaction() == null) {
			$sender->sendMessage("§r§c§l(!) §r§cNo faction!");

			$chunkX = $sender->getPosition()->getFloorX() >> 4;
			$chunkZ = $sender->getPosition()->getFloorZ() >> 4;
			$f = $session->getFaction();
			$total = "§a-Faction Map-\n";

			for ($z = -3; $z < 4; $z++) {
				for ($x = -3; $x < 4; $x++) {
					$cf = FactionManager::getInstance()->getFactionByChunk($chunkX + $x, $chunkZ + $z);
					$c = "§7";

					if ($cf !== null) {
						if ($f->getId() === $cf) {
							$c = "§2";
						} else if ($f->isAlly(FactionManager::getInstance()->getFaction($cf))) {
							$c = "§b";
						} else if ($f->isEnemy(FactionManager::getInstance()->getFaction($cf))) {
							$c = "§c";
						} else {
							$c = "§e";
						}
					}
					if ($x === 0 && $z === 0) $c = "§a";
					$total .= $c . "⬛ ";
				}
				$total .= "\n";
			}
			$total .= "\n§a⬛ §f⇨ You\n§2⬛ §f⇨ Your Faction Claims\n§7⬛ §f⇨ Open\n§c⬛ §f⇨ Enemy\n§b⬛ §f⇨ Ally\n§e⬛ §f⇨ Others";
			$sender->sendMessage($total);
		}
	}
}