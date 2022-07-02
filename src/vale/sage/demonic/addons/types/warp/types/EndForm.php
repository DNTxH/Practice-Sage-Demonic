<?php

namespace vale\sage\demonic\addons\types\warp\types;

use form\MenuForm;
use form\MenuOption;
use pocketmine\player\Player;


class EndForm extends MenuForm
{

	/**
	 * @param Player $player
	 */
	public function __construct(Player $player)
	{
		$options = [
			new MenuOption("§r§8§lCONFIRM \n §r§7Click to board flight"),
			new MenuOption("§r§8§lDENY \n §r§7Click to cancel flight"),
		];
		$m = "§r§e-100§r§e§lx, §r§e-149§r§e§lz \n \n §r§dA Demonic world inhabited \n §r§dby rare monsters and very \n §r§ddangerous PvP masters. \n \n §r§c§lWARNING: §r§cYou cannot teleport away, \n §r§cTHE ONLY way out is the Exit Portal. \n \n §r§e§l0 §r§enearby Player(s) \n §r§c§l0 §r§cPvP Enabled Players(s) \n \n §r§7Click to teleport to this warp!";
		parent::__construct("§r§d§lEnd /warp",$m, $options);
	}

    /**
     * @param Player $player
     * @param int $selectedOption
     * @return void
     */
	public function onSubmit(Player $player, int $selectedOption): void
	{
		if ($selectedOption === 0) {
			$player->sendMessage("sucess");
		}
	}
}