<?php

declare(strict_types=1);

namespace vale\sage\demonic\commands\types\sender;
use pocketmine\block\Air;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\player\Player;

class FlyCommand extends Command
{
	/**
	 * @param CommandSender $sender
	 * @param string $aliasUsed
	 * @param array $args
	 */
	public function execute(CommandSender $sender, string $aliasUsed, array $args): void
	{
		if (!$sender instanceof Player) return;
		$block = $sender->getWorld()->getBlock($sender->getLocation()->subtract(0, 1, 0));
		if($block instanceof Air){
			$sender->sendMessage("§r§c§l(!) §r§cYou cannot use /fly if you are not on a solid block.");
			return;
		}
		$sender->sendMessage("§r§c§l(!) §r§cThe /fly command is currently disabled.");
	}
}