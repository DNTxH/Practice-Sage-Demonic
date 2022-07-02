<?php

declare(strict_types=1);

namespace vale\sage\demonic\commands\types\sender;

use CortexPE\Commando\args\BaseArgument;
use CortexPE\Commando\args\TestArg;
use CortexPE\Commando\BaseCommand;
use CortexPE\Commando\BaseSubCommand;
use pocketmine\command\CommandSender;
use pocketmine\player\Player;
use pocketmine\Server;
use pocketmine\utils\TextFormat;
use vale\sage\demonic\addons\types\brag\Brag;
use vale\sage\demonic\addons\types\envoys\Envoy;
use vale\sage\demonic\addons\types\warp\WarpForm;
use vale\sage\demonic\Loader;

class CBragCommand extends BaseCommand
{

	public function prepare(): void
	{
		$this->registerArgument(0, new TestArg("player", true));
	}

	public function onRun(CommandSender $sender, string $aliasUsed, array $args): void
	{
		if (!$sender instanceof Player) {
			return;
		}
		if (!isset($args["player"])) {
			$sender->sendMessage("§r§c§l(!) §r§c/seebrag <player>");
			$sender->sendMessage("§r§7You can view players inventorys if they have bragged recently.");
			return;
		}
		if (isset($args["player"])) {
			if (!$player = Server::getInstance()->getPlayerByPrefix($args["player"])) {
				$sender->sendMessage("§r§c§l(!) §r§c" . $args["player"] . " is not locally online.");
				return;
			}
			If(!Brag::isBragging($player)){
				$sender->sendMessage("§r§c§l(!) §r§c{$player->getName()} hasn't recently [brag]ged.");
				return;
			}
			$brag = Brag::setBragging($player);
			$brag->createbragMenu($sender);
		}
	}
}