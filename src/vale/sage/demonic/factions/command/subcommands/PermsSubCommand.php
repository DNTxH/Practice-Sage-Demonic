<?php
namespace vale\sage\demonic\factions\command\subcommands;

use CortexPE\Commando\args\IntegerArgument;
use CortexPE\Commando\args\TestArg;
use CortexPE\Commando\BaseSubCommand;
use pocketmine\player\Player;
use pocketmine\command\CommandSender;
use pocketmine\Server;
use vale\sage\demonic\Loader;


class HelpSubCommand extends BaseSubCommand
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
		if (!isset($args["page"])) {
			$message = [
				"§r§d_____________.[ §r§bFactions Help (1/3) ].§r§d_____________",
				"§r§b/f help,h,? §r§d[page = 1] §r§7display a help message",
				"§r§b/f create <name> §r§7create a new faction",
				"§r§b/f who <name> §r§7show faction information",
				"§r§b/f tag <name> §r§7change faction name",
				"§r§b/f join <name> §r§7join a faction",
				"§r§b/f list §r§7list all factions",
				"§r§b/f top §r§7view richest factions",
				"§r§b/f map §r§7map of the surrounding area",
				"§r§b/f sethome §r§7set faction home",
				"§r§b/f home §r§7go to faction home",
				"§r§b/f title <player> <title> §r§7set a player's title",
				"§r§b/f ban §r§7ban a player from faction",
				"§r§b/f unban §r§7unban a player from faction",
				"§r§b/f banlist §r§7see all banned players",
				"§r§b/f lowpower §r§7see player's with power under max in faction",
				"§r§b/f coords §r§7broadcast location to faction",
				"§r§b/f showclaims §r§7list all claims from faction"
			];
			foreach ($message as $line) {
				$sender->sendMessage($line);
			}
			return;
		}
		if (isset($args["page"])) {
			switch ($args["page"]) {
				case 0:
					$sender->sendMessage("§r§cpage must be > 0, got '0'");
					break;
				case 1:
					$message = [
						"§r§d_____________.[ §r§bFactions Help (1/3) ].§r§d_____________",
						"§r§b/f help,h,? §r§d[page = 1] §r§7display a help message",
						"§r§b/f create <name> §r§7create a new faction",
						"§r§b/f who <name> §r§7show faction information",
						"§r§b/f tag <name> §r§7change faction name",
						"§r§b/f join <name> §r§7join a faction",
						"§r§b/f list §r§7list all factions",
						"§r§b/f top §r§7view richest factions",
						"§r§b/f map §r§7map of the surrounding area",
						"§r§b/f sethome §r§7set faction home",
						"§r§b/f home §r§7go to faction home",
						"§r§b/f title <player> <title> §r§7set a player's title",
						"§r§b/f ban §r§7ban a player from faction",
						"§r§b/f unban §r§7unban a player from faction",
						"§r§b/f banlist §r§7see all banned players",
						"§r§b/f lowpower §r§7see player's with power under max in faction",
						"§r§b/f coords §r§7broadcast location to faction",
						"§r§b/f showclaims §r§7list all claims from faction"
					];
					foreach ($message as $line) {
						$sender->sendMessage($line);
					}
					break;
				case 3:
					$message = [
						"§r§d_____________.[ §r§bFactions Help (2/3) ].§r§d_____________",
						"§r§b/f leave, §r§7leave faction",
						"§r§b/f invite <player> §r§7invite a player to faction",
						"§r§b/f deinvite <player> §r§7revoke invitation from player",
						"§r§b/f claim §r§7claim a land for your faction",
						"§r§b/f unclaim §r§7unlciam land from your faction",
						"§r§b/f kick <player> §r§7kick player from faction",
						"§r§b/f mod <player> §r§7set a player to mod in faction",
						"§r§b/f coleader <player> §r§7set a player to coleader in faction",
						"§r§b/f leader <player> §r§7set a player to leader in faction",
						"§r§b/f chat <faction : ally : public> §r§7switch chat modes",
						"§r§b/f warp §r§7open faction warps menu",
						"§r§b/f setwarp §r§7set a warp",
						"§r§b/f perms §r§7change what players can do in your claims",
						"§r§b/f upgrades §r§7upgrade perks in your faction",
						"§r§b/f tntfill <radius> <amount> §r§7tnt fill into tnt bank",
						"§r§b/f chest §r§7open faction chest",
					];
					foreach ($message as $line) {
						$sender->sendMessage($line);
					}
					break;
								case 2:
					$message = [
						"§r§d_____________.[ §r§bFactions Help (3/3) ].§r§d_____________",
						"§r§b/f weewoo, §r§7alert members in faction about a raid",
						"§r§b/f deinvite <player> §r§7revoke invitation from player",
						"§r§b/f invsee §r§7see a faction member's inventory",
						"§r§b/f stealth §r§7go into stealth mode to not disable enemies flight",
						"§r§b/f wild §r§7teleport to a random location"
					];
					foreach ($message as $line) {
						$sender->sendMessage($line);
					}
					break;
			}
		}
	}
}