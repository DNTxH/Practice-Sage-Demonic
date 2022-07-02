<?php

declare(strict_types = 1);
namespace vale\sage\demonic\entitys\types;

use pocketmine\{event\entity\EntityDamageEvent,
	nbt\tag\CompoundTag,
	network\mcpe\protocol\types\entity\EntityIds,
	Player,
	Server};
use pocketmine\entity\{EntitySizeInfo,Living};
use pocketmine\entity\Location;

class TextEntity extends Living
{

	/** @var float * */
	public $height = 0.1;
	/** @var float * */
	public $width = 0.1;

	public $scale = 0.0001;

	/** @var float * */
	protected $gravity = 0.00;

	public ?string $text = null;

	public function __construct(Location $location, ?CompoundTag $nbt = null, ?string $text = null){
		$this->setNameTagAlwaysVisible(true);
		$this->setImmobile(true);
		parent::__construct($location,$nbt);
	}

	public function getText(): string{
		return $this->getNameTag();
	}

	public function attack(EntityDamageEvent $source): void
	{
		$source->cancel();
	}

	/**
	 * @param string $text
	 */
	public function updateText(string $text): void{
		$this->setNameTag($text);
	}


	protected function getInitialSizeInfo(): EntitySizeInfo
	{
		return new EntitySizeInfo(0,0,0);
	}

	public static function getNetworkTypeId(): string
	{
		return EntityIds::CAT;
	}

	public function getName(): string
	{
		return  "Floating";
	}
}
