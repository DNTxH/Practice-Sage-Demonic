<?php

namespace vale\sage\demonic\addons\types\warp;

use form\FormIcon;
use form\MenuOption;
use pocketmine\player\Player;
use vale\sage\demonic\addons\types\warp\types\EndForm;

class WarpForm extends \form\MenuForm
{

	/**
	 * @param Player $player
	 */
	public function __construct(Player $player)
	{
		$options = [
			new MenuOption("§r§3§lSage Lounge \n §r§7The Sage Lounge",new FormIcon("textures/items/chorus_fruit",FormIcon::IMAGE_TYPE_PATH)),
			new MenuOption("§r§3§lOutpost \n §r§724/7 capturable PvP zones",new FormIcon("textures/items/diamond_helmet",FormIcon::IMAGE_TYPE_PATH)),
			new MenuOption("§r§5§lEnd \n §r§8-207§lx§r§8, 307§l§8z",new FormIcon("textures/blocks/endframe_eye",FormIcon::IMAGE_TYPE_PATH)),
			new MenuOption("§r§4§lWarzone \n §r§8-207§lx§r§8, 307§l§8z",new FormIcon("textures/items/diamond_sword",FormIcon::IMAGE_TYPE_PATH)),
			new MenuOption("§r§2§lThe Plains \n §r§8-207§lx§r§8, 307§l§8z",new FormIcon("textures/blocks/tallgrass",FormIcon::IMAGE_TYPE_PATH))
		];
		parent::__construct("§r§7Sage Warps","§r§fChoose a destination",$options);
	}

    /**
     * @param Player $player
     * @param int $selectedOption
     * @return void
     */
	public function onSubmit(Player $player, int $selectedOption): void
	{
		if($selectedOption === 0){
			$player->sendMessage("spawn");
		}

		if($selectedOption === 1){
			$player->sendMessage("outpost");
		}
		if($selectedOption === 2){
			$end = new EndForm($player);
			$player->sendForm($end);
		}
		if($selectedOption === 3){
			$player->sendMessage("WARZONe");
		}
		if($selectedOption === 4){
			$player->sendMessage("PLAINS");
		}
	}
}