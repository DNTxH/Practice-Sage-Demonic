<?php
namespace vale\sage\demonic\sessions;
use pocketmine\player\Player;
use vale\sage\demonic\sessions\player\SessionPlayer;

class SessionManager
{
	/** @var array $session */
	public array $session = [];

	/**
	 * @param Player $player
	 */
	public function createSession(Player $player)
	{
		if ($this->getSession($player) === null) {
			$this->session[$player->getName()] = new SessionPlayer($player);
		}
	}

	/**
	 * @param Player $player
	 * @return SessionPlayer|null
	 */
	public function getSession(Player $player): ?SessionPlayer
	{
		if (isset($this->session[$player->getName()])) {
			return $this->session[$player->getName()];
		}
		return null;
	}

	public function removeSession(Player $player)
	{
		unset($this->session[$player->getName()]);
	}
}