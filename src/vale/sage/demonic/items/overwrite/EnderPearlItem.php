<?php
namespace vale\sage\demonic\items\overwrite;
use vale\sage\demonic\entitys\types\CustomEnderPearlEntity;
use pocketmine\block\Block;
use pocketmine\entity\Location;
use pocketmine\entity\projectile\Throwable;
use pocketmine\item\ItemIdentifier;
use pocketmine\item\ItemIds;
use pocketmine\item\ItemUseResult;
use pocketmine\item\ProjectileItem;
use pocketmine\math\Vector3;
use pocketmine\player\Player;


/**
 * Class EnderPearlItem
 * @package core\item\overwrite
 * @author Jibix
 * @date 08.01.2022 - 23:42
 * @project Genesis
 */
class EnderPearlItem extends ProjectileItem{

    public function __construct(){
        parent::__construct(new ItemIdentifier(ItemIds::ENDER_PEARL, 0), "Ender Pearl");
    }

    /**
     * Function createEntity
     * @param Location $location
     * @param Player $thrower
     * @return Throwable
     */
    public function createEntity(Location $location, Player $thrower): Throwable{
        return new CustomEnderPearlEntity($location, $thrower, null, $location);
    }

    /**
     * Function getThrowForce
     * @return float
     */
    public function getThrowForce(): float{
        return 1.9;
    }

    /**
     * Function getMaxStackSize
     * @return int
     */
    public function getMaxStackSize(): int{
        return 16;
    }
}