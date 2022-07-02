<?php
namespace vale\sage\demonic\addons\types\inventorys;

use muqsit\invmenu\InvMenu;
use muqsit\invmenu\type\InvMenuTypeIds;
use pocketmine\player\Player;
use vale\sage\demonic\Loader;

class DisposalInventory{

	/**
	 * @param Player $player
	 */
	public static function open(Player $player){
		$menu = InvMenu::create(InvMenuTypeIds::TYPE_DOUBLE_CHEST)
			->setName("§r§8Disposal");
		$menu->send($player);
		Loader::playSound($player,"mob.villager.idle",1);
		$menu->setInventoryCloseListener(function () use ($menu, $player){
			$count = count($menu->getInventory()->getContents());
			$player->sendMessage("§r§6§lA Total of §r§c$count §r§6Item(s), will be disposed.");
		});
	}
}