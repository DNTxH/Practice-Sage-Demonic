<?php

namespace vale\sage\demonic\addons\types\regions;

use MongoDB\Driver\Server;
use vale\sage\demonic\addons\types\regions\exception\AreaException;
use vale\sage\demonic\Loader;
use vale\sage\demonic\addons\types\regions\Area;
use pocketmine\world\Position;

class RegionManager {

	/** @var Loader */
	private $core;

	/** @var Area[] */
	private array $areas = [];

	/**
	 * AreaManager constructor.
	 *
	 * @param Loader $core
	 *
	 * @throws AreaException
	 */
	public function __construct(Loader $core) {
		$this->core = $core;
		$core->getServer()->getPluginManager()->registerEvents(new RegionListener($core), $core);
		$this->init();
	}

	/**
	 * @throws AreaException
	 */
	public function init(): void {
		$this->addArea(new Area("Spawn", new Position(7, 164, 4, $this->core->getServer()->getWorldManager()->getDefaultWorld()), new Position(182, 251, 131, $this->core->getServer()->getWorldManager()->getDefaultWorld()), false, false));
	}

	/**
	 * @param Area $area
	 */
	public function addArea(Area $area): void {
		$this->areas[] = $area;
	}

	/**
	 * @param Position $position
	 *
	 * @return Area[]|null
	 */
	public function getAreasInPosition(Position $position): ?array {
		$areas = $this->getAreas();
		$areasInPosition = [];
		foreach($areas as $area) {
			if($area->isPositionInside($position) === true) {
				$areasInPosition[] = $area;
			}
		}
		if(empty($areasInPosition)) {
			return null;
		}
		return $areasInPosition;
	}

	/**
	 * @return Area[]
	 */
	public function getAreas(): array {
		return $this->areas;
	}
}