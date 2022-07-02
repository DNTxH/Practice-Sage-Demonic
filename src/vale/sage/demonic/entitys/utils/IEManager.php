<?php

declare(strict_types = 1);

namespace vale\sage\demonic\entitys\utils;


use pocketmine\entity\Skin;
use pocketmine\entity\Location;
use pocketmine\world\World;
use pocketmine\utils\TextFormat;
use vale\sage\demonic\entitys\utils\SkinConverter;
use vale\sage\demonic\Loader;

class IEManager {


	/** @var Skin */
	public $skin;

	/** @var string */
	public $name;

	/** @var Loader*/
	private $plugin;

	/**
	 * Manager constructor.
	 *
	 * @param Loader $plugin
	 * @param string $path
	 */
	public function __construct(Loader $plugin, string $path) {
		$this->plugin = $plugin;
		$this->path = $path;
		$this->init();
	}

	public function init(): void {
		$path = $this->plugin->getDataFolder() . $this->path;
		$this->skin = SkinConverter::createSkin(SkinConverter::getSkinDataFromPNG($path));

	}
}
