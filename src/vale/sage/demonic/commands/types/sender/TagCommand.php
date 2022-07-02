<?php

declare(strict_types=1);

namespace vale\sage\demonic\commands\types\sender;

use CortexPE\Commando\args\BaseArgument;
use CortexPE\Commando\args\RawStringArgument;
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
use pocketmine\world\sound\XpCollectSound;
use pocketmine\world\sound\XpLevelUpSound;
use vale\sage\demonic\addons\types\customenchants\CELoader;
use vale\sage\demonic\Loader;

class TagCommand extends BaseCommand
{

	public function prepare(): void
	{
		$this->registerArgument(0, new RawStringArgument("name", true));
	}

	public function onRun(CommandSender $sender, string $aliasUsed, array $args): void
	{
		if (!$sender instanceof Player) {
			return;
		}
		if (!isset($args["name"])) {
			$sender->sendMessage("§r§c§l(!) §r§cYou must specify a tag name [length <= 12 char(s)]");
			$sender->sendMessage("§r§7Remember, any inappropriate tag names will result in a ban.");
			return;
		}
		if (isset($args["name"]) && strlen($args["name"]) > 12) {
			$formatted = TextFormat::colorize($args["name"]);
			$sender->sendMessage("§r§c§l(!) §r§cThe tag name '$formatted' §r§cmust be shorten then 12 character(s).");
			$sender->sendMessage("§r§7Purchase a rank to unlock access to longer tag names.");
			return;
		}
		if (isset($args["name"]) && empty($args["player"])) {
			$formatted = TextFormat::colorize($args["name"]);
			$sender->sendMessage("§r§b§lYou've SUCCESSFULLY set your tag to $formatted §r§b!");
			$sender->sendMessage("§r§7Now show off your sexy tag to your friends in chat!");
			$ses = Loader::getInstance()->getSessionManager()->getSession($sender);
			$ses->setCurrentag($formatted);
			$ses->getPlayer()->getWorld()->addSound($ses->getPlayer()->getLocation(),new XpLevelUpSound(1000));
			return;
		}
	}
}