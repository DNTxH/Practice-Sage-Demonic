<?php

declare(strict_types=1);

namespace vale\sage\demonic\commands\types\sender;

use CortexPE\Commando\args\BaseArgument;
use CortexPE\Commando\args\TestArg;
use CortexPE\Commando\BaseCommand;
use CortexPE\Commando\BaseSubCommand;
use pocketmine\command\CommandSender;
use pocketmine\entity\effect\Effect;
use pocketmine\entity\effect\EffectManager;
use pocketmine\entity\effect\PoisonEffect;
use pocketmine\network\mcpe\convert\TypeConverter;
use pocketmine\player\Player;
use pocketmine\Server;
use pocketmine\utils\TextFormat;
use vale\sage\demonic\addons\types\customenchants\CELoader;
use vale\sage\demonic\Loader;

class BlessCommand extends BaseCommand
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
			$this->bless($sender);
			return;
		}
		if (isset($args["player"])) {
			if (!$player = Server::getInstance()->getPlayerByPrefix($args["player"])) {
				$sender->sendMessage("§r§c§l(!) §r§c" . $args["player"] . " is not locally online.");
				return;
			}
			if (isset(self::$bless[$player->getName()]) && microtime(true) - self::$bless[$player->getName()] <= 30) {
				$delayMessage = round(30 - abs(self::$bless[$player->getName()] - microtime(true)), 2);
				$sender->sendMessage("§r§c§l(§c!§c§l)" . " §r§cYou cannot use /bless for another " . $delayMessage . "ms!");
				$sender->sendMessage("§r§7Purchase a rank at §r§cshop.sagepe.com §r§7to reduce the delay between using /bless!");
				return;
			}
			$this->bless($player);
			$sender->sendMessage("§r§e§l(!) §r§eYou have succesfully blessed {$player->getScoreTag()}.");
			self::$bless[$sender->getName()] = microtime(true);
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