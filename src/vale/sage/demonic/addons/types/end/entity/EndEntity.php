<?php



declare(strict_types = 1);

namespace vale\sage\demonic\addons\types\end\entity;



use pocketmine\entity\EntitySizeInfo;
use pocketmine\entity\Living;
use pocketmine\network\mcpe\protocol\types\entity\EntityIds;

class EndEntity extends Living
{

	const NETWORK_ID = EntityIds::WITHER_SKELETON;

    /**
     * @return EntitySizeInfo
     */
	protected function getInitialSizeInfo(): EntitySizeInfo
	{
		return new EntitySizeInfo(1,1,1);
	}

    /**
     * @return string
     */
	public static function getNetworkTypeId(): string
	{
		return EntityIds::WITHER_SKELETON;
	}

    /**
     * @return string
     */
	public function getName(): string
	{
	return "LOL";
	}
}
