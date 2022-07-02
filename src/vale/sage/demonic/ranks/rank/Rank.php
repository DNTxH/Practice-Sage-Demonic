<?php

namespace vale\sage\demonic\ranks\rank;

use pocketmine\player\Player;
use pocketmine\utils\TextFormat;
use vale\sage\demonic\Loader;

class Rank implements RankIDS {

	/** @var string */
	private $name;

	/** @var string */
	private $coloredName;

	/** @var int */
	private $identifier;

	/** @var int */
	private $deathBanTime;

	/** @var string */
	private $chatFormat;

	/** @var string */
	private $tagFormat;

	/** @var array */
	private $permissions;

	/**
	 * Group constructor.
	 *
	 * @param string $name
	 * @param string $coloredName
	 * @param int $identifier
	 * @param int $deathBanTime
	 * @param string $chatFormat
	 * @param string $tagFormat
	 * @param array $permissions
	 *
	 */
	public function __construct(string $name, string $coloredName, int $identifier, int $deathBanTime, string $chatFormat, string $tagFormat, array $permissions = []) {
		$this->name = $name;
		$this->coloredName = $coloredName;
		$this->identifier = $identifier;
		$this->deathBanTime = $deathBanTime;
		$this->chatFormat = $chatFormat;
		$this->tagFormat = $tagFormat;
		$this->permissions = $permissions;
	}

	/**
	 * @return string
	 */
	public function getName(): string {
		return $this->name;
	}

	/**
	 * @return string
	 */
	public function getColoredName(): string {
		return $this->coloredName;
	}

	/**
	 * @return int
	 */
	public function getIdentifier(): int {
		return $this->identifier;
	}

	/**
	 * @return int
	 */
	public function getDeathBanTime(): int {
		return $this->deathBanTime;
	}

	/**
	 * @param Player $player
	 * @param string        $message
	 * @param array         $args
	 *
	 * @return string
	 */
	public function getChatFormatFor(Player $player, string $message, array $args = []): string {
		$man = Loader::getInstance()->getSessionManager()->getSession($player);
		$tag = $man->getCurrentTag();
		if($tag == null){
			$tag = "§r§6#2021";
		}
		$format = $this->chatFormat;
		foreach($args as $arg => $value) {
			$format = str_replace("{" . $arg . "}", $value, $format);
		}
		$format = str_replace("{player}", $player->getDisplayName(), $format);
		$format = str_replace("{tag}", $tag, $format);
		return str_replace("{message}", $message, $format);
	}

	/**
	 * @param Player $player
	 * @param array         $args
	 *
	 * @return string
	 */
	public function getTagFormatFor(Player $player, array $args = []): string {
		$man = Loader::getInstance()->getSessionManager()->getSession($player);
		$tag = $man->getCurrentTag();
		if($tag == null){
			$tag = "#2021";
		}
		$format = $this->tagFormat;
		foreach($args as $arg => $value) {
			$format = str_replace("{" . $arg . "}", $value, $format);
		}
		$format = str_replace("{tag}", $tag, $format);
		return str_replace("{player}", $player->getDisplayName(), $format);
	}

	/**
	 * @return string[]
	 */
	public function getPermissions(): array {
		return $this->permissions;
	}

	/**
	 * @return string
	 */
	public function __toString() {
		return $this->name;
	}
}