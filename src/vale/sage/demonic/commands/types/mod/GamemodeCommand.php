<?php

declare(strict_types=1);

namespace vale\sage\demonic\commands\types\mod;
use CortexPE\Commando\args\RawStringArgument;
use CortexPE\Commando\args\TestArg;
use CortexPE\Commando\BaseCommand;
use CortexPE\Commando\exception\ArgumentOrderException;
use pocketmine\command\CommandSender;
use pocketmine\player\GameMode;
use pocketmine\player\Player;
use pocketmine\Server;
use vale\sage\demonic\Loader;

class GamemodeCommand extends BaseCommand
{

	public int $bal;

	/**
	 * @throws ArgumentOrderException
	 */
	public function prepare(): void
	{
		$this->registerArgument(0, new TestArg("player", true));
		$this->registerArgument(1, new RawStringArgument("mode",true));
	}

	public function onRun(CommandSender $sender, string $aliasUsed, array $args): void
	{
		if (!$sender instanceof Player) {
			return;
		}

		if(!Loader::getInstance()->getServer()->isOp($sender->getName())){
			$sender->sendMessage("§r§c§l(!) §r§cThis command is only available to moderators.");
			$sender->sendMessage("§r§7Running this command again will flag your account for trying to exploit.");
			return;
		}
		if(!isset($args["mode"])){
			$sender->sendMessage("§r§cYou must provide a valid Gamemode");
			$sender->sendMessage("§r§7List of Game Modes: survival, creative");
			return;
		}

		$gameMode = GameMode::fromString($args["mode"]);
		if($gameMode === null){
			$sender->sendMessage("§r§c§l(!) §r§cThe Gamemode 'null' is invalid!");
			$sender->sendMessage("§r§7Avaliable Gamemodes: Survival, S, Creative, C.");
			return;
		}

		if (isset($args["player"])) {
			if (!$player = Server::getInstance()->getPlayerByPrefix($args["player"])) {
				$sender->sendMessage("§r§c§l(!) §r§c" . $args["player"] . " is not locally online.");
				return;
			}
			$sender->sendMessage("§r§a§l(!) §r§aThe player {$player->getName()}'s Gamemode has been updated.");
			$player->setGamemode(GameMode::fromString($args["mode"]));
			$player->sendMessage("§d» §7(§l§eGAMEMODE CHANGE§r§7) §d«");
			$player->sendMessage("§r§7Your gamemode has been set to: ". $args["mode"]);
		}
	}
}