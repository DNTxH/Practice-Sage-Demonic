<?php

declare(strict_types=1);

namespace vale\sage\demonic\commands\types\sender;

use CortexPE\Commando\args\BaseArgument;
use CortexPE\Commando\args\IntegerArgument;
use CortexPE\Commando\args\RawStringArgument;
use CortexPE\Commando\args\TestArg;
use CortexPE\Commando\BaseCommand;
use CortexPE\Commando\BaseSubCommand;
use pocketmine\command\CommandSender;
use pocketmine\player\Player;
use pocketmine\Server;
use pocketmine\utils\TextFormat;
use vale\sage\demonic\addons\types\envoys\events\JackPotEvent;
use vale\sage\demonic\commands\types\subcommands\JackPotBuySubCommand;
use vale\sage\demonic\commands\types\subcommands\JackPotStatsSubCommand;
use vale\sage\demonic\commands\types\subcommands\JackPotTopSubCommand;
use vale\sage\demonic\commands\types\subcommands\WithdrawAllSubCommand;
use vale\sage\demonic\Loader;
use vale\sage\demonic\rewards\Rewards;

class JackPotCommand extends BaseCommand
{

	public function prepare(): void
	{
		$this->registerSubCommand(new JackPotTopSubCommand("top"));
		$this->registerSubCommand(new JackPotStatsSubCommand("stats"));
		$this->registerSubCommand(new JackPotBuySubCommand("buy"));
	}

	public function onRun(CommandSender $sender, string $aliasUsed, array $args): void
	{
		if (!$sender instanceof Player) {
			return;
		}
		JackPotEvent::getInstance()->formatMessage($sender);
	}
}