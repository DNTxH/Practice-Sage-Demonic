<?php

namespace vale\sage\demonic\addons\types\warp\types;

use form\FormIcon;
use form\MenuForm;
use form\MenuOption;
use muqsit\invmenu\InvMenu;
use pocketmine\item\Item;
use pocketmine\item\ItemFactory;
use pocketmine\network\mcpe\convert\TypeConverter;
use pocketmine\player\Player;
use vale\sage\demonic\addons\types\envoys\events\JackPotEvent;


class ItemInfoForm extends MenuForm
{

    /** @var int */
	public int $amount;

    /** @var int */
	public int $tickets;

    /** @var InvMenu */
	public InvMenu $menu;

    /** @var Player */
	public Player $basePlayer;

	/**
	 * @param Player $player
	 * @param Item $item
	 * @param InvMenu $menu
	 * @param Player $baseplayer
	 */
	public function __construct(Player $player, Item $item, InvMenu $menu, Player $baseplayer)
	{
		$options = [
			new MenuOption("§r§8§lSUBMIT \n §r§7Click to return"),
		];
		$this->basePlayer = $baseplayer;
		$this->menu = $menu;
		$lorecount = count($item->getLore());
		$lol = "";
		foreach ($item->getLore() as $line => $value){
			$lol.= "$value\n";
		}
		$type = ItemFactory::getInstance()->get($item->getId(), $item->getMeta(), $item->getCount())->getName();
		$m = "§r§f§lType§r§f: {$type} \n  \n §r§f§lLore§r§f: $lorecount lores \n \n$lol";
		parent::__construct("§r§7{$baseplayer->getName()}'s brag", $m, $options);
	}

    /**
     * @param Player $player
     * @param int $selectedOption
     * @return void
     */
	public function onSubmit(Player $player, int $selectedOption): void
	{
		if ($selectedOption === 0) {
			$this->menu->send($player);
		}
	}
}