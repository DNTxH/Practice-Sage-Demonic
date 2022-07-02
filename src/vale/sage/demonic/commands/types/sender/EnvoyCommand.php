<?php

declare(strict_types=1);

namespace vale\sage\demonic\commands\types\sender;
use CortexPE\Commando\args\RawStringArgument;
use CortexPE\Commando\args\TestArg;
use CortexPE\Commando\BaseCommand;
use pocketmine\command\CommandSender;
use pocketmine\player\Player;
use vale\sage\demonic\addons\types\envoys\Envoy;
use vale\sage\demonic\Loader;

class EnvoyCommand extends BaseCommand
{

	public function prepare(): void
	{
		$this->registerArgument(0, new RawStringArgument("start", true));
	}

	public function onRun(CommandSender $sender, string $aliasUsed, array $args): void
	{
		if (!$sender instanceof Player) {
			return;
		}
		$last = time() - Envoy::getInstance()->getLastEnovyTime();
		if ($last === null) {
			$sender->sendMessage("LAST IS NULL");
			return;
		}
		if(!isset($args["start"])) {
			$previous = "§r§b§l(!) PREVIOUS SAGE ENVOY: §r§d" . self::secondsToTime((int)$last);
			$end = "§r§5§lNEXT END ENVOY: §r§d" . self::secondsToTime(700);
			$sender->sendMessage("$previous");
			$sender->sendMessage($end);
			$sender->sendMessage("§r§7Kill Ender Monsters to reduce this timer.");
			return;
		}
		if(isset($args["start"]) && !Loader::getInstance()->getServer()->isOp($sender->getName())){
			$sender->sendMessage("§r§c§l(!) §r§cThis command is only available to moderators.");
			$sender->sendMessage("§r§7Running this command again will flag your account for trying to exploit.");
			return;
		}
		$sender->sendMessage("§r§aAn envoy will begin shortly");
		Envoy::getInstance()->setTime(5);
	}

	/**
	 * @param int $secs
	 * @return string
	 */
	public static
	function secondsToTime(int $secs)
	{
		$s = $secs % 60;
		$m = floor(($secs % 3600) / 60);
		$h = floor(($secs % 86400) / 3600);

		return "$h h, $m m, $s s";
	}
}