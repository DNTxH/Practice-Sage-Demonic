<?php

namespace vale\sage\demonic\addons\types\brag;

use pocketmine\player\Player;
use vale\sage\demonic\addons\types\brag\user\BragUser;

class Brag {

    /** @var array */
	public static array $bragging = [];

	/**
	 * @param Player $player
	 * @return mixed|BragUser
	 */
	public static function setBragging(Player $player)
	{
		if (!isset(self::$bragging[$player->getName()])) {
			self::$bragging[$player->getName()] = new BragUser($player);
		}
		return self::$bragging[$player->getName()];
	}

	/**
	 * @param Player $player
	 */
	public static function destroyBrag(Player $player){
		if(self::isBragging($player)){
			unset(self::$bragging[$player->getName()]);
		}
	}

	/**
	 * @param Player $player
	 * @return bool
	 */
	public static function isBragging(Player $player): bool{
		return isset(self::$bragging[$player->getName()]);
	}
}