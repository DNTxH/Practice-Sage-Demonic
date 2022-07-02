<?php

namespace vale\sage\demonic;

use vale\sage\demonic\cchest\CollectionManager;
use vale\sage\demonic\database\SqliteDatabase;
use vale\sage\demonic\enchants\factory\EnchantFactoryListener;
use vale\sage\demonic\crate\CrateManager;
use vale\sage\demonic\enchants\CustomEnchantListener;
use vale\sage\demonic\enchants\enchantments\EnchantmentsManager;
use vale\sage\demonic\levels\LevelsListener;
use CortexPE\Commando\exception\HookAlreadyRegistered;
use CortexPE\Commando\PacketHooker;
use JsonException;
use muqsit\invmenu\InvMenuHandler;
use muqsit\invmenu\type\util\InvMenuTypeBuilders;
use pocketmine\block\BlockFactory;
use pocketmine\block\BlockLegacyIds;
use pocketmine\entity\Entity;
use pocketmine\network\mcpe\protocol\PlaySoundPacket;
use pocketmine\network\mcpe\protocol\types\inventory\WindowTypes;
use pocketmine\player\Player;
use pocketmine\utils\Config;
use pocketmine\utils\TextFormat;
use vale\sage\demonic\addons\AddonManager;
use vale\sage\demonic\commands\defaults\staff\StaffManager;
use vale\sage\demonic\addons\types\envoys\Envoy;
use vale\sage\demonic\addons\types\chatGames\ChatGames;
use vale\sage\demonic\koth\Koth;
use vale\sage\demonic\addons\types\regions\RegionManager;
use vale\sage\demonic\sets\manager\AbilityManager;
use vale\sage\demonic\sets\manager\EntityManager;
use vale\sage\demonic\sets\manager\WeaponManager;
use vale\sage\demonic\privatevault\db\PrivateVaultDB;
use vale\sage\demonic\sets\manager\ArmorManager;
use vale\sage\demonic\slotbot\SlotBotManager;
use vale\sage\demonic\slotbot\utils\SlotBotUtils;
use vale\sage\demonic\commands\CommandManager;
use vale\sage\demonic\entitys\EntityRegistery;
use vale\sage\demonic\factions\FactionManager;
use vale\sage\demonic\floatingtext\TManager;
use pocketmine\plugin\PluginBase;
use vale\sage\demonic\listeners\ListenerRegistry;
use vale\sage\demonic\provider\Database;
use vale\sage\demonic\ranks\RankManager;
use vale\sage\demonic\sessions\SessionManager;
use vale\sage\demonic\spawner\SpawnerManager;
use vale\sage\demonic\tasks\TaskRegistery;
use pocketmine\world\Position;
use vale\sage\demonic\Trojan\Phase;
use vale\sage\demonic\Partner\EventHandle;
use vale\sage\demonic\Trojan\TrojanEventListener;
use vale\sage\demonic\Trojan\AntiGlitchEvent;
use vale\sage\demonic\staff\StaffModeListener;
use pocketmine\entity\EntityFactory;
use vale\sage\demonic\Partner\Entity\Bard;
use pocketmine\world\World;
use pocketmine\nbt\tag\CompoundTag;
use pocketmine\item\ItemFactory;
use vale\sage\demonic\Partner\Item\SnowBall;
use pocketmine\entity\EntityDataHelper;
use pocketmine\item\ItemIdentifier;
use vale\sage\demonic\ChunkBuster\Listener\ChunkBusterEventListener;
use pocketmine\item\ItemIds;
use vale\sage\demonic\Trojan\Fly\Esoteric;

class Loader extends PluginBase
{
	public const JOINS = "         \n                           §r§6§lSage §r§7| §r§eJoins\n";
	public const SPACE = "    ";
	public const BUYCRAFT = "sage.shop.pe";
	const PERM_PREFIX = TextFormat::RED . "(!)";
	const REG_CMD_PREFIX = self::PERM_PREFIX;

	/** @var Loader|null $instance */
	public static ?Loader $instance = null;

    /** @var SlotBotManager */
    private static SlotBotManager $slotBotManager;

    /** @var StaffManager */
    private static StaffManager $staffManager;

    /** @var SlotBotUtils */
    private static SlotBotUtils $slotBotUtils;

    /** @var ChatGames */
    protected static ChatGames $chatGames;

    /** @var Koth */
    private static Koth $koth;

    /** @var array */
    public array $commandSpy = [];

    /** @var array */
    public array $cooldowns = [];

    /** @var array */
    public array $tpa = [];

    /** @var array */
    public static array $staffMode = [];

    /** @var array */
    public static array $savedInventories = [];

    /** @var array */
    public static array $savedPositons = [];

    /** @var array */
    public static array $vanish = [];

    /** @var array */
    public static array $ninja_hit = [];

    /** @var array */
    public static array $bard_allow = [];

    /** @var array */
    public static array $meezoid = [];

    /** @var array */
    public static array $comboAbility = [];

    /** @var array */
    public static array $antiTrap = [];

    /** @var array|array[] */
    public static array $cooldown = array(
        "Ninja" => [],
        "Bard" => [],
        "SnowBall" => [],
        "HateFoo" => [],
        "Guardian" => [],
        "MeeZoid" => [],
        "ComboAbility" => [],
        "NotRamix" => [],
        "AntiTrap" => [],
    );

    /** @var array */
    public static array $partner_cooldown = [];

    /** @var array */
    public static array $enderPearl = [];

    /** @var array */
    public static array $trojan = [
        "cps" => [
            "cache" => [],
            "cps" => []
        ],
        "moving"=> [
            "cache" => [],
            "moving" => []
        ],
        "reach" => []
    ];

	/** @var Database|null $database */
	private ?Database $database = null;

	/** @var SessionManager|null $sessionManager */
	private ?SessionManager $sessionManager = null;

	/** @var ListenerRegistry $registry */
	public ListenerRegistry $registry;

    /** @var AddonManager|null */
	private ?AddonManager $addonManager;

    /** @var RankManager|null */
	private ?RankManager $rankManager;

	/** @var RegionManager|null $manager */
	private ?RegionManager $regionManager;

    /** @var FactionManager|null */
	private ?FactionManager $factionManager;

    /** @var EnchantmentsManager|null */
    private ?EnchantmentsManager $enchantmentsManager;

    /** @var CrateManager */
    private CrateManager $cratesManager;

    /** @var PrivateVaultDB */
    private PrivateVaultDB $privateVaultDB;

    /** @var CollectionManager */
    private CollectionManager $collectionManager;

    /** @var SpawnerManager */
    private SpawnerManager $spawnerManager;

    /** @var SqliteDatabase */
    private SqliteDatabase $sqliteDatabase;

    /** @var Esoteric */
    private Esoteric $esoteric;

    /** @var Config */
	public Config $data;

    /** @var string */
	public const TYPE_DISPENSER = "myplugin:dispenser";

    /** @var string */
    public const INV_MENU_TYPE_WORKBENCH = "myplugin:enderchest";


	public function onLoad(): void {
	
		self::$instance = $this;
        $this->saveDefaultConfig();
        self::$koth = new Koth();
        self::$slotBotUtils = new SlotBotUtils();
		$this->database = new Database($this);
        \vale\sage\demonic\database\Database::init();
        $this->sqliteDatabase = new SqliteDatabase($this);
	}

	/**
	 * @throws HookAlreadyRegistered
	 * @throws addons\types\regions\exception\AreaException|JsonException
	 */
	public function onEnable(): void
	{
        self::init();
	}
    
    private static function init() : void {
        if (!PacketHooker::isRegistered()) PacketHooker::register(self::$instance);
        if (!InvMenuHandler::isRegistered()) InvMenuHandler::register(self::$instance);
        self::$instance->getConfig()->set("uptime", time());
        self::$instance->getConfig()->save();
        if(!self::$instance->getConfig()->exists("map_age")){
            self::$instance->getConfig()->set("map_age", time());
            self::$instance->getConfig()->save();
        }
        self::initBlockInvs();
        TManager::initCrates();
        EntityRegistery::init();
        self::$chatGames = new ChatGames();
        self::$chatGames->startGames();
        TaskRegistery::init();
        self::initManagers();
        self::initListeners();
        self::initItemEntities();
        self::$instance->registry = new ListenerRegistry(self::$instance);
    }

    /**
     * @return void
     * @throws addons\types\regions\exception\AreaException
     */
    private static function initManagers() : void {
        self::$instance->addonManager = new AddonManager(self::$instance);
        TManager::init();
        CommandManager::init();
        \vale\sage\demonic\Trojan\command\CommandManager::init();
        AbilityManager::getInstance()->init();
        ArmorManager::getInstance()->init();
        EntityManager::getInstance()->init();
        WeaponManager::getInstance()->init();
        self::$staffManager = new StaffManager();
        self::$slotBotManager = new SlotBotManager();
        self::$instance->enchantmentsManager = new EnchantmentsManager(self::$instance);
        self::$instance->rankManager = new RankManager(self::$instance);
        self::$instance->regionManager = new RegionManager(self::$instance);
        self::$instance->factionManager = new FactionManager(self::$instance);
        self::$instance->sessionManager = new SessionManager();
        self::$instance->cratesManager = new CrateManager(self::$instance);
        self::$instance->privateVaultDB = new PrivateVaultDB(self::$instance);
        self::$instance->collectionManager = new CollectionManager(self::$instance);
        self::$instance->spawnerManager = new SpawnerManager(self::$instance);
        //Esoteric::init(self::$instance, self::$instance->getConfig(), true);
    }

    private static function initListeners() : void {
        new SageListener(self::$instance);
        self::$instance->getServer()->getPluginManager()->registerEvents(new LevelsListener(), self::$instance);
        self::$instance->getServer()->getPluginManager()->registerEvents(new CustomEnchantListener(), self::$instance);
        self::$instance->getServer()->getPluginManager()->registerEvents(new EnchantFactoryListener(), self::$instance);
        self::$instance->getServer()->getPluginManager()->registerEvents(new EventHandle(), self::$instance);
        self::$instance->getServer()->getPluginManager()->registerEvents(new TrojanEventListener(), self::$instance);
        self::$instance->getServer()->getPluginManager()->registerEvents(new AntiGlitchEvent(), self::$instance);
        self::$instance->getServer()->getPluginManager()->registerEvents(new Phase(), self::$instance);
        self::$instance->getServer()->getPluginManager()->registerEvents(new StaffModeListener(), self::$instance);
        self::$instance->getServer()->getPluginManager()->registerEvents(new ChunkBusterEventListener(self::$instance), self::$instance);
    }

    private static function initBlockInvs() : void {
        InvMenuHandler::getTypeRegistry()->register(self::TYPE_DISPENSER, InvMenuTypeBuilders::BLOCK_FIXED()
            ->setBlock(BlockFactory::getInstance()->get(BlockLegacyIds::DISPENSER, 0))
            ->setSize(9)
            ->setNetworkWindowType(WindowTypes::DISPENSER)
            ->build());
    }

    private static function initItemEntities() : void {
        EntityFactory::getInstance()->register(Bard::class, function(World $world, CompoundTag $nbt): Bard{
            return new Bard(EntityDataHelper::parseLocation($nbt,$world));
        },["bard"]);
        ItemFactory::getInstance()->register(new SnowBall(new ItemIdentifier(ItemIds::SNOWBALL,1)),"snowball",["snowball"]);
    }

    /**
     * @return StaffManager
     */
	public static function getStaffManager(): StaffManager{
		return self::$staffManager;
	}

	public function onDisable(): void
	{
		Envoy::getInstance()->removeSkyDrops();
        \vale\sage\demonic\database\Database::closeDatabase();
        $this->getCollectionManager()->save();
	}

    /**
     * @return FactionManager|null
     */
	public function getFactionsManager(): ?FactionManager{
		return $this->factionManager;
	}

    /**
     * @return RegionManager|null
     */
	public function getRegionManager(): ?RegionManager
	{
		return $this->regionManager;
	}

	/**
	 * @return RankManager|null
	 */
	public function getRankManager(): ?RankManager
	{
		return $this->rankManager;
	}

	/**
	 * @return AddonManager|null
	 */
	public function getAddonManager(): ?AddonManager
	{
		return $this->addonManager;
	}

	/**
	 * @return SessionManager|null
	 */
	public function getSessionManager(): ?SessionManager
	{
		return $this->sessionManager;
	}

	/**
	 * @return Database|null
	 */
	public function getMysqlProvider(): ?Database
	{
		return $this->database;
	}

    /**
     * @return Koth
     */
    public static function getKoth() : Koth {
        return self::$koth;
    }

    /**
     * @return ChatGames
     */
    public static function getChatGames() : ChatGames {
        return self::$chatGames;
    }

    /**
     * @return SlotBotManager
     */
    public static function getSlotBotManager() : SlotBotManager {
        return self::$slotBotManager;
    }

    /**
     * @return EnchantmentsManager
     */
    public static function getEnchantmentsManager() : EnchantmentsManager{
        return self::$instance->enchantmentsManager;
    }

    /**
     * @return CrateManager
     */
    public static function getCrateManager() : CrateManager {
        return self::$instance->cratesManager;
    }

    /**
     * @return PrivateVaultDB
     */
    public static function getPrivateVaultDB(): PrivateVaultDB {
        return self::$instance->privateVaultDB;
    }

    /**
     * @return CollectionManager
     */
    public function getCollectionManager() : CollectionManager {
        return $this->collectionManager;
    }

    /**
     * @return SpawnerManager
     */
    public function getSpawnerManager() : SpawnerManager {
        return $this->spawnerManager;
    }

    /**
     * @return SqliteDatabase
     */
    public function getSqliteDatabase() : SqliteDatabase {
        return $this->sqliteDatabase;
    }

    /**
     * @return SlotBotUtils
     */
    public static function getSlotBotUtils() : SlotBotUtils {
        return self::$slotBotUtils;
    }

	/**
	 * @return static
	 */
	public static function getInstance(): self
	{
		return self::$instance;
	}


	/**
	 * @param int $secs
	 * @return string
	 */
	public static function secondsToTime(int $secs)
	{
		$s = $secs % 60;
		$m = floor(($secs % 3600) / 60);
		$h = floor(($secs % 86400) / 3600);
		$d = floor(($secs % 2592000) / 86400);
		return "$d days, $h hours, $m minutes, $s seconds";
	}

	/**
	 * @param Entity $player
	 * @param string $sound
	 * @param int $volume
	 * @param int $pitch
	 * @param int $radius
	 */
	public static function playSound(Entity $player, string $sound, $volume = 1, $pitch = 1, int $radius = 5): void
	{
		foreach ($player->getWorld()->getNearbyEntities($player->getBoundingBox()->expandedCopy($radius, $radius, $radius)) as $p) {
			if ($p instanceof Player) {
				if ($p->isOnline()) {
					$spk = new PlaySoundPacket();
					$spk->soundName = $sound;
					$spk->x = $p->getLocation()->getX();
					$spk->y = $p->getLocation()->getY();
					$spk->z = $p->getLocation()->getZ();
					$spk->volume = $volume;
					$spk->pitch = $pitch;
					$p->getNetworkSession()->sendDataPacket($spk);
				}
			}
		}
	}

    /**
     * @param Position $position
     * @return string
     */
    public function positionToString(Position $position): string{
        return $position->getX().":".$position->getY().":".$position->getZ().":".$position->getWorld()->getFolderName();
    }

    /**
     * @param string $position
     * @return Position|null
     */
    public function stringToPosition(string $position): ?Position{
        $explode = explode(":", $position);
        if(!$explode) return null;
        $this->getServer()->getWorldManager()->loadWorld((string)$explode[3]);
        return new Position((int)$explode[0], (int)$explode[1], (int)$explode[2], $this->getServer()->getWorldManager()->getWorldByName((string)$explode[3]));
    }
}