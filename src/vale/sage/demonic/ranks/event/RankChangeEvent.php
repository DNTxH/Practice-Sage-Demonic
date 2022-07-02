<?php

namespace vale\sage\demonic\ranks\event;

use pocketmine\event\Event;
use pocketmine\player\Player;

class RankChangeEvent extends Event {

	/** @var Player */
	private $player;

	/** @var int */
	private $groupId;

	/**
	 * GroupChangeEvent constructor.
	 *
	 * @param Player $player
	 * @param int $groupId
	 */
	public function __construct(Player $player, int $groupId) {
		$this->player = $player;
		$this->groupId = $groupId;
	}

	/**
	 * @return Player
	 */
	public function getPlayer(): Player {
		return $this->player;
	}

	/**
	 * @return int
	 */
	public function getRankId(): int {
		return $this->groupId;
	}
}