<?php

declare(strict_types = 1);

namespace vale\sage\demonic\factions;

use pocketmine\world\format\Chunk;
use vale\sage\demonic\Loader;

class Claim {

	/** @var Chunk */
	private $chunk;

	/** @var Faction */
	private $faction;

	/**
	 * Claim constructor.
	 *
	 * @param int $chunkX
	 * @param int $chunkZ
	 * @param Faction $faction
	 */
	public function __construct(int $chunkX, int $chunkZ, Faction $faction) {
		$level = Loader::getInstance()->getServer()->getWorldManager()->getDefaultWorld();
		$this->chunk = $level->getChunk($chunkX, $chunkZ);
		$this->faction = $faction;
	}

	/**
	 * @return Chunk
	 */
	public function getChunk(): Chunk {
		return $this->chunk;
	}

	/**
	 * @return Faction
	 */
	public function getFaction(): Faction {
		return $this->faction;
	}

	/**
	 * @param Faction $faction
	 */
	public function setFaction(Faction $faction): void {
		$this->faction = $faction;
	}
}