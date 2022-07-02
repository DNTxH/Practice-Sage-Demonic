<?php

declare(strict_types=1);

namespace vale\sage\demonic\commands\types\sender;

use CortexPE\Commando\args\IntegerArgument;
use CortexPE\Commando\BaseCommand;
use pocketmine\command\CommandSender;
use pocketmine\player\Player;
use pocketmine\world\sound\XpLevelUpSound;
use vale\sage\demonic\Loader;
use vale\sage\demonic\rewards\Rewards;

class SplitSoulsCommand extends BaseCommand
{

	public function prepare(): void
	{
		$this->registerArgument(0, new IntegerArgument("amount", true));
	}

	public function onRun(CommandSender $sender, string $aliasUsed, array $args): void
	{
		if (!$sender instanceof Player) {
			return;
		}
		$session = Loader::getInstance()->getSessionManager()->getSession($sender);
		$balance = number_format($session->getBalance(), 2);
		if (!isset($args["amount"])) {
			$sender->sendMessage("§r§e§lSouls: §r§f" . $balance . "§r§7 Souls(s).");
			$sender->sendMessage("§r§7Use /splitsouls <amount>");
			return;
		}
		$value = $args["amount"];
		if($session->getSouls() < $value){
			$sender->sendMessage("§r§c§l(!) §r§cYou don't have sufficient souls!");
			return;
		}
		if($value < 0){
			return;
		}
		$sender->getWorld()->addSound($sender->getLocation(),new XpLevelUpSound(10000));
		$sender->sendMessage("§r§aYou have signed a sage voucher worth $value souls(s)!");
		$sender->getInventory()->addItem(Rewards::createSoulVoucher($sender,$args["amount"]));
	}
}