<?php

declare(strict_types=1);

namespace vale\sage\demonic\commands\types\sender;

use CortexPE\Commando\args\TestArg;
use CortexPE\Commando\BaseCommand;
use pocketmine\command\CommandSender;
use pocketmine\item\ItemIds;
use pocketmine\player\Player;
use pocketmine\Server;
use pocketmine\world\sound\AnvilUseSound;
use vale\sage\demonic\addons\types\customenchants\CELoader;

class RepairCommand extends BaseCommand
{

	public static array $bless = [];

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
			$string = (string)"";
			$hand = $sender->getInventory()->getItemInHand();
			if($hand->hasCustomName()) $string = $hand->getCustomName();
			if(!$hand->hasCustomName()) $string = $hand->getName();
			if($hand->getId() === ItemIds::AIR) return;
			$hand->setDamage(0);
			$sender->sendMessage("§r§e§l/fix: §r§eYou have successfully repaired your: $string");
			$sender->getInventory()->setItemInHand($hand);
			$sender->getWorld()->addSound($sender->getLocation(), new AnvilUseSound());
			return;
		}
		if (isset($args["player"])) {
			if (!$player = Server::getInstance()->getPlayerByPrefix($args["player"])) {
				$sender->sendMessage("§r§c§l(!) §r§c" . $args["player"] . " is not locally online.");
				return;
			}
			$string = (string)"";
			$hand = $player->getInventory()->getItemInHand();
			if($hand->hasCustomName()) $string = $hand->getCustomName();
			if(!$hand->hasCustomName()) $string = $hand->getName();
			if($hand->getId() === ItemIds::AIR) return;
			$player->sendMessage("§r§e§l/fix: §r§7[{$sender->getName()}] §r§ehas successfully repaired your: $string");
			$sender->sendMessage("§r§e§l** REPAIRED **");
			$hand->setDamage(0);
			$player->getWorld()->addSound($sender->getLocation(), new AnvilUseSound());
			$player->getInventory()->setItemInHand($hand);
		}
	}

	/**
	 * @param Player $player
	 */
	public function bless(Player $player): void
	{
		if(isset(self::$bless[$player->getName()]) && microtime(true) - self::$bless[$player->getName()] <= 30){
			$delayMessage = round(30 - abs(self::$bless[$player->getName()] - microtime(true)), 2);
			$player->sendMessage("§r§c§l(!) §r§cYou can run this command again in $delayMessage SEC(s).");
			return;
		}
		self::$bless[$player->getName()] = microtime(true);
		$player->sendMessage("§r§e§l(!) §r§eYou have been §r§e§l** BLESSED **");
		foreach ($player->getEffects()->all() as $effect) {
			if ($effect->getType()->isBad()){
				$player->getEffects()->remove($effect->getType());
				$level = EnchantmentsManager::roman($effect->getAmplifier());
				$player->sendMessage("§r§c§l[-] §r§7{$effect->getType()->getName()->getText()} $level");
			}
		}
	}
}