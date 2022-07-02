<?php

namespace vale\sage\demonic\spawner;

use vale\sage\demonic\Loader;
use vale\sage\demonic\spawner\entity\IronGolem;
use vale\sage\demonic\spawner\entity\Blaze;
use vale\sage\demonic\spawner\entity\Creeper;
use vale\sage\demonic\spawner\entity\Enderman;
use vale\sage\demonic\spawner\entity\ZombiePigman;
use vale\sage\demonic\spawner\entity\CaveSpider;
use vale\sage\demonic\spawner\entity\Spider;
use vale\sage\demonic\spawner\entity\Skeleton;
use vale\sage\demonic\spawner\entity\Zombie;
use vale\sage\demonic\spawner\entity\Wolf;
use vale\sage\demonic\spawner\entity\Pig;
use vale\sage\demonic\spawner\entity\Chicken;
use vale\sage\demonic\spawner\entity\Sheep;
use vale\sage\demonic\spawner\entity\Cow;
use vale\sage\demonic\spawner\tile\MobSpawner;
use vale\sage\demonic\spawner\block\MonsterSpawner;
use pocketmine\block\BlockFactory;
use pocketmine\block\BlockIdentifier;
use pocketmine\block\BlockLegacyIds;
use pocketmine\block\BlockBreakInfo;
use pocketmine\block\BlockToolType;
use pocketmine\block\tile\TileFactory;
use pocketmine\entity\Entity;
use pocketmine\entity\EntityDataHelper;
use pocketmine\entity\EntityFactory;
use pocketmine\entity\Location;
use pocketmine\item\Item;
use pocketmine\item\ItemFactory;
use pocketmine\item\ItemIds;
use pocketmine\item\ToolTier;
use pocketmine\nbt\tag\CompoundTag;
use pocketmine\nbt\tag\DoubleTag;
use pocketmine\nbt\tag\FloatTag;
use pocketmine\nbt\tag\ListTag;
use pocketmine\math\Vector3;
use pocketmine\utils\TextFormat as C;
use pocketmine\world\World;
class SpawnerManager
{
	public array $spawners = [
		"irongolem" => IronGolem::class,
		"blaze" => Blaze::class,
		"creeper" => Creeper::class,
		"enderman" => Enderman::class,
		"zombiepigman" => ZombiePigman::class,
		"cavespider" => CaveSpider::class,
		"spider" => Spider::class,
		"skeleton" => Skeleton::class,
		"zombie" => Zombie::class,
		"wolf" => Wolf::class,
		"pig" => Pig::class,
		"chicken" => Chicken::class,
		"sheep" => Sheep::class,
		"cow" => Cow::class,
	];
	private array $registeredSpawners = [];
	
	public function __construct(private Loader $loader){
		TileFactory::getInstance()->register(MobSpawner::class, ["MobSpawner", "minecraft:mob_spawner"]);
		BlockFactory::getInstance()->register(new MonsterSpawner(new BlockIdentifier(BlockLegacyIds::MOB_SPAWNER, 0, null, MobSpawner::class), "Monster Spawner", new BlockBreakInfo(5.0, BlockToolType::PICKAXE, ToolTier::WOOD()->getHarvestLevel())), true);
		$loader->getServer()->getPluginManager()->registerEvents(new SpawnerListener($loader), $loader);
		$this->init();
	}
	
	public function init(): void{
		foreach($this->spawners as $type => $class){
			EntityFactory::getInstance()->register($class, function(World $world, CompoundTag $nbt) use ($class): Entity{
				return new $class(EntityDataHelper::parseLocation($nbt, $world), $nbt);
			}, array_merge([$class], [$type]));
			$this->registeredSpawners[$type] = $class;
		}
	}
	
	public function getRegisteredEntities(): ?array{
        $reflectionProperty = new \ReflectionProperty(EntityFactory::class, 'creationFuncs');
        $reflectionProperty->setAccessible(true);
        return array_keys($reflectionProperty->getValue(EntityFactory::getInstance()));
    }
	
	public function getSpawner(string $name, int $amount = 1): Item{
		$nbt = CompoundTag::create()->setString("Entity", $name);
		$spawner = ItemFactory::getInstance()->get(ItemIds::MOB_SPAWNER, 0, $amount, $nbt);
        $spawner->setCustomName(C::RESET . C::GREEN . C::BOLD . SpawnerUtils::getEntityName($name) . C::RESET . C::WHITE . " Spawner");
		$spawner->setLore([
			C::RESET . " ",
			C::RESET . C::YELLOW . C::BOLD . "INFO",
			C::RESET . C::YELLOW . "  * " . C::WHITE . "Type: " . C::YELLOW . SpawnerUtils::getEntityName($name),
			C::RESET . C::YELLOW . "  * " . C::WHITE . "Drops: " . C::YELLOW . SpawnerUtils::getEntityDrop($name),
			// C::RESET . " ",
			// C::RESET . C::GRAY . C::BOLD . "Right-Click" . C::RESET . " on a block to place this spawner down",
			
		]);
        return $spawner;
    }
	
	public function createEntity(string $type, Location $location, CompoundTag $nbt): ?Entity{
		if (isset($this->registeredSpawners[$type])) {
            $class = $this->registeredSpawners[$type];
			return new $class($location, $nbt);
        }
        return null;
	}
	
	public function createBaseNBT(Vector3 $pos, ?Vector3 $motion = null, float $yaw = 0.0, float $pitch = 0.0): CompoundTag {
        return CompoundTag::create()
            ->setTag("Pos", new ListTag([
                new DoubleTag($pos->x),
                new DoubleTag($pos->y),
                new DoubleTag($pos->z)
            ]))
            ->setTag("Motion", new ListTag([
                new DoubleTag($motion !== null ? $motion->x : 0.0),
                new DoubleTag($motion !== null ? $motion->y : 0.0),
                new DoubleTag($motion !== null ? $motion->z : 0.0)
            ]))
            ->setTag("Rotation", new ListTag([
                new FloatTag($yaw),
                new FloatTag($pitch)
            ]));
    }
}