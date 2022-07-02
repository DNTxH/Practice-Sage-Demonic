<?php
namespace vale\sage\demonic\sets\manager;

use vale\sage\demonic\entitys\types\CustomFallingBlockEntity;
use vale\sage\demonic\entitys\types\LightningBoltEntity;
use pocketmine\block\BlockFactory;
use pocketmine\data\bedrock\EntityLegacyIds;
use pocketmine\entity\EntityDataHelper;
use pocketmine\entity\EntityFactory;
use pocketmine\entity\object\FallingBlock;
use pocketmine\nbt\tag\CompoundTag;
use pocketmine\utils\SingletonTrait;
use pocketmine\world\World;


/**
 * Class EntityManager
 * @package vale\sage\demonic\sets\manager
 * @author Jibix
 * @date 06.01.2022 - 19:45
 * @project Genesis
 */
class EntityManager{
    use SingletonTrait;

    public function init(): void{
        EntityFactory::getInstance()->register(CustomFallingBlockEntity::class, function(World $world, CompoundTag $nbt): CustomFallingBlockEntity{
            return new CustomFallingBlockEntity(EntityDataHelper::parseLocation($nbt, $world), FallingBlock::parseBlockNBT(BlockFactory::getInstance(), $nbt), $nbt);
        }, ['FallingSand', 'minecraft:falling_block'], EntityLegacyIds::FALLING_BLOCK);
        EntityFactory::getInstance()->register(LightningBoltEntity::class, function (World $world, CompoundTag $nbt): LightningBoltEntity{
            return new LightningBoltEntity(EntityDataHelper::parseLocation($nbt, $world), 15, $nbt);
        }, ["LightningBoltEntity"]);
      /*  EntityFactory::getInstance()->register(EnderPearlEntity::class, function(World $world, CompoundTag $nbt): EnderPearlEntity{
            return new EnderPearlEntity(EntityDataHelper::parseLocation($nbt, $world), null, $nbt);
        }, ['ThrownEnderpearl', 'minecraft:ender_pearl'], EntityLegacyIds::ENDER_PEARL); */
    }
}