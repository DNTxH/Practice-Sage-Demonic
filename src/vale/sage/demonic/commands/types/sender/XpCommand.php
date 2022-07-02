<?php

declare(strict_types=1);

namespace vale\sage\demonic\commands\types\sender;

use CortexPE\Commando\args\BaseArgument;
use CortexPE\Commando\args\TestArg;
use CortexPE\Commando\BaseCommand;
use CortexPE\Commando\BaseSubCommand;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\command\utils\CommandException;
use pocketmine\lang\Translatable;
use pocketmine\player\Player;
use pocketmine\Server;
use pocketmine\utils\TextFormat;
use vale\sage\demonic\addons\types\envoys\events\JackPotEvent;
use vale\sage\demonic\Loader;

class XpCommand extends Command
{

	public function __construct()
	{
		parent::__construct("xp", "view your xp", "ok", ["exp", "myxp"]);
	}

	public function execute(CommandSender $sender, string $commandLabel, array $args)
	{
		if (!$sender instanceof Player) {
			return;
		}
		$exp = number_format($sender->getXpManager()->getCurrentTotalXp(), 1);
		$level = $sender->getXpManager()->getXpLevel();
		$levelup = self::getExpToLevelUp($sender->getXpManager()->getCurrentTotalXp());
		$sender->sendMessage($sender->getNameTag() . " §r§6has §r§c$exp EXP §r§6(level §r§c" . $level . "§r§6) §r§6and needs $levelup more exp to level up.");
	}

	/**
	 * @param int $level
	 * @return int
	 */
	public static function getExpToLevelUp(int $level): int
	{
		if ($level <= 15) {
			return 2 * $level + 7;
		} else if ($level <= 30) {
			return 5 * $level - 38;
		} else {
			return 9 * $level - 158;
		}
	}
}

