<?php

namespace vale\sage\demonic\addons\types\warp\types;

use form\FormIcon;
use form\MenuForm;
use form\MenuOption;
use pocketmine\player\Player;
use vale\sage\demonic\addons\types\envoys\events\JackPotEvent;


class JackpotForm extends MenuForm
{

    /** @var int */
	public int $amount;

    /** @var int */
	public int $tickets;

	/**
	 * @param Player $player
	 */
	public function __construct(Player $player, int $amount, int $tickets)
	{
		$options = [
			new MenuOption("§r§2Confirm Purchase"),
			new MenuOption("§r§4Cancel Purchase"),
		];
		$this->tickets = $tickets;
		$this->amount = $amount;
		$price = number_format($amount,2);
		$m = "§r§6§lSage's Ticket Merchant \n §r§fYou are about to purchase §r§2$tickets §r§fticket(s) §r§for §r§2$$amount!";
		parent::__construct("§r§8Confirm Ticket Purchase",$m, $options);
	}

    /**
     * @param Player $player
     * @param int $selectedOption
     * @return void
     */
	public function onSubmit(Player $player, int $selectedOption): void
	{
		if ($selectedOption === 0) {
			JackPotEvent::getInstance()->addTickets($player,$this->tickets, $this->amount);
			return;
		}
	}
}