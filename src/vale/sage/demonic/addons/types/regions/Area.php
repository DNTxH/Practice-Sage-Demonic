<?php

namespace vale\sage\demonic\addons\types\regions;

use pocketmine\world\World;
use pocketmine\world\Position;
use vale\sage\demonic\addons\types\regions\exception\AreaException;

class Area {

	/** @var string */
	private $name;

	/** @var Position */
	private Position $firstPosition;

	/** @var Position */
	private Position $secondPosition;

	/** @var bool */
	private bool $pvpFlag;

	/** @var bool */
	private bool $editFlag;

	/** @var World|null */
	private ?World $level;


	/**
	 * Area constructor.
	 *
	 * @param string $name
	 * @param Position $firstPosition
	 * @param Position $secondPosition
	 * @param bool $pvpFlag
	 * @param bool $editFlag
	 *
	 * @throws AreaException
	 */
	public function __construct(string $name, Position $firstPosition, Position $secondPosition, bool $pvpFlag, bool $editFlag) {
		$this->firstPosition = $firstPosition;
		$this->secondPosition = $secondPosition;
		$this->name = $name;
		$this->level = $firstPosition->getWorld()->getFolderName() === $secondPosition->getWorld()->getFolderName() ? $firstPosition->getWorld() : null;
		if($this->level === null) {
			throw new AreaException("Area \"$name\"'s first position's level does not equal the second position's level.");
		}
		$this->pvpFlag = $pvpFlag;
		$this->editFlag = $editFlag;
	}

	/**
	 * @param Position $position
	 *
	 * @return bool
	 */
	public function isPositionInside(Position $position): bool {
		$level = $position->getWorld();
		$firstPosition = $this->firstPosition;
		$secondPosition = $this->secondPosition;
		$minX = min($firstPosition->getX(), $secondPosition->getX());
		$maxX = max($firstPosition->getX(), $secondPosition->getX());
		$minY = min($firstPosition->getY(), $secondPosition->getY());
		$maxY = max($firstPosition->getY(), $secondPosition->getY());
		$minZ = min($firstPosition->getZ(), $secondPosition->getZ());
		$maxZ = max($firstPosition->getZ(), $secondPosition->getZ());
		return $minX <= $position->getX() and $maxX >= $position->getFloorX() and $minY <= $position->getY() and
			$maxY >= $position->getY() and $minZ <= $position->getZ() and $maxZ >= $position->getFloorZ() and
			$this->level->getFolderName() === $level->getFolderName();
	}

	/**
	 * @return Position
	 */
	public function getFirstPosition(): Position {
		return $this->firstPosition;
	}

	/**
	 * @return Position
	 */
	public function getSecondPosition(): Position {
		return $this->secondPosition;
	}

	/**
	 * @return World
	 */
	public function getLevel(): World {
		return $this->level;
	}

	/**
	 * @return string
	 */
	public function getName(): string {
		return $this->name;
	}

	/**
	 * @return bool
	 */
	public function getPvpFlag(): bool {
		return $this->pvpFlag;
	}

	/**
	 * @return bool
	 */
	public function getEditFlag(): bool {
		return $this->editFlag;
	}
}