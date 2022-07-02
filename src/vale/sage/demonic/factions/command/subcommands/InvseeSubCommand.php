<?php
namespace vale\sage\demonic\factions\command\subcommands;

use CortexPE\Commando\args\IntegerArgument;
use CortexPE\Commando\args\RawStringArgument;
use CortexPE\Commando\args\TestArg;
use CortexPE\Commando\BaseSubCommand;
use pocketmine\player\Player;
use pocketmine\command\CommandSender;
use pocketmine\Server;
use vale\sage\demonic\Loader;
use muqsit\invmenu\InvMenu;
use pocketmine\inventory\Inventory;


class InvseeSubCommand extends BaseSubCommand
{

	/**
	 * @throws \CortexPE\Commando\exception\ArgumentOrderException
	 */
	protected function prepare(): void
	{
		$this->registerArgument(0, new RawStringArgument("name",true));
	}

	public function onRun(CommandSender $sender, string $commandLabel, array $args): void {
		if(!$sender instanceof Player) {
			return;
		}
		if(!isset($args["name"])){
			$sender->sendMessage("§r§c§l(!) §r§cInvalid usage, example: \n §r§b/f invsee §r§d<inventory;armor:string>");
			return;
		}
		if(!isset($args["type"])) {
			$sender->sendMessage("§r§c§l(!) §r§cInvalid usage, example: \n §r§b/f invsee §r§d<inventory;armor:string>");
			return;
		}
		$session = Loader::getInstance()->getSessionManager()->getSession($sender);
		if($session->getFaction() === null) {
			$sender->sendMessage("§r§c§l(!) §r§cNo faction!");
			return;
		}
		if($args["name"] == $sender->getName()) {
			$sender->sendMessage("§r§c§l(!) §r§cYou can't invsee yourself!");
			return;
		}
		$owner = Loader::getInstance()->getServer()->getPlayerByPrefix($args["name"]);

		if($owner === null) {
			$sender->sendMessage(C::RED . C::BOLD . "(!) " . C::RESET . C::RED . "Player not found");
			return;
		}
		if(!$session->getFaction()->isMember($args["name"])) {
			$sender->sendMessage("§r§c§l(!) §r§cPlayer " . $args["name"] . " is not from your faction!");
			return;
		}
		switch(strtolower($args["type"])){
			case "inventory":
			case "inv":
				$menu = InvMenu::create(InvMenu::TYPE_DOUBLE_CHEST);
				$menu->setName(C::RESET . $owner->getName() . "'s Inventory");
				$menu->getInventory()->setContents($owner->getInventory()->getContents());
				$menu->send($sender);
				$menu->setInventoryCloseListener(function(Player $player, Inventory $inventory)use($owner): void{
					$owner->getInventory()->setContents($inventory->getContents());
				});
				break;
			case "armor":
				$menu = InvMenu::create(InvMenu::TYPE_HOPPER);
				$menu->setName(C::RESET . $owner->getName() . "'s Armor");
				$menu->getInventory()->setContents($owner->getArmorInventory()->getContents());
				$menu->send($sender);
				$menu->setInventoryCloseListener(function(Player $player, Inventory $inventory)use($owner): void{
					$owner->getArmorInventory()->setContents([$inventory->getItem(0), $inventory->getItem(1), $inventory->getItem(2), $inventory->getItem(3)]);
				});
				break;
			default:
				$sender->sendMessage("§r§c§l(!) §r§cInvalid usage, example: \n §r§b/f invsee §r§d<inventory;armor:string> <name:string>");
		}
	}
}