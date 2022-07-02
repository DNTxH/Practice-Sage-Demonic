<?php
namespace vale\sage\demonic\sessions\player;

use DateTime;
use DaveRandom\CallbackValidator\BuiltInTypes;
use form\Utils;
use muqsit\invmenu\InvMenu;
use muqsit\invmenu\type\InvMenuTypeIds;
use pocketmine\entity\effect\EffectInstance;
use pocketmine\entity\effect\VanillaEffects;
use pocketmine\permission\Permission;
use pocketmine\player\Player;
use pocketmine\Server;
use pocketmine\world\sound\EnderChestCloseSound;
use pocketmine\world\sound\EnderChestOpenSound;
use vale\sage\demonic\database\Database;
use vale\sage\demonic\factions\Faction;
use vale\sage\demonic\listeners\types\CooldownListener;
use vale\sage\demonic\Loader;

class SessionPlayer
{

	/** @var string $region */
	public string $region = "";

	/** @var null|Faction */
	private $faction = null;

	/** @var null|int */
	private $factionRole = null;

	/** @var array $permissions */
	private array $permissions = [];

	private ?string $currentTag = null;

	private ?int $jackpotEarnings;

	private ?int $jackpotWins;

	public array $lifestealmask = [];

	/**
	 * @param Player|null $player
	 * @param string|null $user
	 * @param int|null $rank
	 * @param int|null $balance
	 * @param string|null $bundle
	 * @param int|null $reclaim
	 * @param int|null $souls
	 * @param int|null $combatTag
	 * @param bool|null $combatTagged
	 * @param int|null $goldenApplesEaten
	 */
	public function __construct(
		private ?Player $player = null,
		private ?string $user = null,
		private ?int $rank = 0,
		private ?int $balance = null,
		private ?string $bundle = "",
		private ?int $reclaim = null,
		private ?int $souls = null,
		private ?int $combatTag = 0,
		private ?bool $combatTagged = false,
		private ?int $goldenApplesEaten = 0,
	)
	{
		$this->user = $this->getPlayer()->getName();
	}

	public function save(bool $async = false)
	{
		$xuid = $this->getPlayer()->getXuid();
		$balance = $this->getBalance();
		$id = $this->getFactionRole() !== null ? $this->factionRole : null;
		$name = $this->getFaction() !== null ? $this->faction->getName() : null;
		$permissions = implode(",", $this->permissions);
		Database::queryAsync("UPDATE players SET players.username = ? WHERE xuid = ?", "ss", [$this->user, $xuid]);
		Database::queryAsync("UPDATE players SET players.rank = ? WHERE xuid = ?", "is", [$this->rank, $xuid]);
		Database::queryAsync("UPDATE players SET players.permissions = ? WHERE xuid = ?", "ss", [$permissions, $xuid]);
		Database::queryAsync("UPDATE players SET players.balance = ? WHERE xuid = ?", "is", [$balance, $xuid]);
		Database::queryAsync("UPDATE players SET players.reclaim = ? WHERE xuid = ?", "is", [$this->reclaim, $xuid]);
		Database::queryAsync("UPDATE players SET players.souls = ? WHERE xuid = ?", "is", [$this->souls, $xuid]);
		Database::queryAsync("UPDATE players SET players.faction = ? WHERE xuid = ?", "ss", [$name, $xuid]);
		Database::queryAsync("UPDATE players SET players.factionRole = ? WHERE xuid = ?", "is", [$id, $xuid]);
		Database::queryAsync("UPDATE players SET players.jackpotwins = ? WHERE xuid = ?", "is", [$this->jackpotWins, $xuid]);
		Database::queryAsync("UPDATE players SET players.jackpotearnings = ? WHERE xuid = ?", "is", [$this->jackpotEarnings, $xuid]);
	}

	/**
	 * Loads the Sessioned Data
	 */
	public function load(): void
	{
		$this->register();
		$xuid = $this->getPlayer()->getXuid();
		$username = $this->getPlayer()->getName();

		Database::queryAsync("SELECT * FROM players WHERE xuid = ?", "s", [$xuid], function (array $rows) {
			if($row = $rows[0] ?? null) {
				unset($row);

				foreach($rows as $row) {
					$this->rank = $row["rank"];
					$this->balance = $row["balance"];
					$this->permissions = explode(",", $row["permissions"]);
					$this->reclaim = $row["reclaim"];
					$this->souls = $row["souls"];
					$this->jackpotWins = $row["jackpotwins"];
					$this->jackpotEarnings = $row["jackpotearnings"];

					if($row["faction"] !== null) {
						$faction = Loader::getInstance()->getFactionsManager()->getFaction($row["faction"]);
						if ($faction !== null) {
							if ($faction->isMember($this->getPlayer()->getName())) {
								$this->faction = $faction;
								$this->factionRole = $row["factionRole"];
							}
						}
					}
				}

				return;
			}
		});

		Database::queryAsync("INSERT INTO players(xuid, username) VALUES(?, ?)", "ss", [$xuid, $username]);
	}



	public function sendDefaultScoreboard(): void
	{
		if(isset(CooldownListener::$goldenapplecooldown[$this->getPlayer()->getName()])){
			$time = time() - CooldownListener::$goldenapplecooldown[$this->getPlayer()->getName()];
			if($time >= 30){
				unset(CooldownListener::$goldenapplecooldown[$this->getPlayer()->getName()]);
				$this->goldenApplesEaten = 0;
			}
		}
		Utils::makeScoreboard($this->getPlayer(), " §r§6§lSage §r§7(§f§f#MAP-1§r§7)", $this->user);
		$t = date('d-m-Y');
		$dayNum = strtolower(date("d", strtotime($t)));
		$day = strtolower(date("D"));
		$month = date("m");
		$dateObj = DateTime::createFromFormat('!m', $month);
		$monthName = $dateObj->format('F'); // March
		$realdate = "§r§7§o" . ucfirst($day) . ", $monthName" . " $dayNum";
		$rank = Loader::getInstance()->getRankManager()->getName($this->getRank());
		$souls = $this->getSouls();
		$faction = $this->getFaction() !== null ? $this->getFaction()->getName() : "None";
		$power = $this->getFaction() !== null ? $this->getFaction()->getStrength() : 0;
		$bal = number_format($this->getBalance());
		$lines = [
			"    $realdate",
			"§r§d  ",
			"  §r§6§lInformation",
			"   §r§7Rank: §r§f$rank ",
			"   §r§7Balance: §r§f$$bal ",
			"   §r§7Souls: §r§f$souls ",
			"§r§e  ",
			"  §r§6§lFaction",
			"  §r§7Faction: §r§f$faction",
			"  §r§7Power: §r§f$power",
			"§r§7  ",
			" §r§6sagehcf.club",

		];
		$i = 1;
		foreach ($lines as $line) {
			Utils::addLine($this->getPlayer(), $i, $line . str_repeat(" ", 2));
			$i++;
		}
	}

	public function setRank(int $rank): void
	{
		$this->rank = $rank;
	}

	/**
	 * Registers a new Player
	 */
	public function  register(): void
	{
		$no = count(glob(Server::getInstance()->getDataPath() . "players/*.dat"));
		$player = $this->getPlayer();
		if (!$this->getPlayer()->hasPlayedBefore()) {
			$message = (Loader::JOINS . Loader::SPACE . "          §l§e*§6* §r§fWelcome §6{$player->getName()} §fto §l§eFactions" . " §r§7(#" . $no + 1 . ") §l§e*§6* ");
			Server::getInstance()->broadcastMessage($message);
		}
		$xuid = $this->getPlayer()->getXuid();
		$username = $this->getPlayer()->getName();
		Database::queryAsync("SELECT * FROM players WHERE xuid = ?", "s", [$xuid], function (array $rows) {
			if($row = $rows[0] ?? null) {
				return;
			}
		});
		Database::queryAsync("INSERT INTO players(xuid, username) VALUES(?, ?)", "ss", [$xuid, $username]);
	}

	/**
	 * @param string $tag
	 */
	public function setCurrentag(string $tag): void
	{
		$this->currentTag = $tag;
	}

	/**
	 * @param bool $value
	 */
	public function combatTag(bool $value): void{
		if($value){
			$this->combatTag = 30;
		}
	}

	public function setCombatTagTime(int $value): void{
		$this->combatTag = $value;
	}

	public function setCombatTagged(bool $value): void{
		$this->combatTagged = $value;
	}

	public function combatTagTime(): int{
		return $this->combatTag;
	}
	/**
	 * @return bool
	 */
	public function isCombatTagged(): bool{
		return $this->combatTagged;
	}

	/**
	 * @return string|null
	 */
	public function getCurrentTag(): ?string
	{
		return $this->currentTag;
	}

	public function openEnderchest(): void{
		$menu = InvMenu::create(InvMenuTypeIds::TYPE_HOPPER);
		$menu->setName("§r§8Ender Chest" . " §r§7[{$this->player->getName()}]");
		$inventory = $menu->getInventory();
		$inventory->setContents($this->player->getEnderInventory()->getContents(true));
		$menu->send($this->player);
		$player = $this->player;
		$menu->setInventoryCloseListener(function () use ($menu, $player){
			$player->getWorld()->addSound($player->getLocation(),new EnderChestCloseSound());
			$player->getEnderInventory()->setContents($menu->getInventory()->getContents(true));
		});
		$player->getWorld()->addSound($player->getLocation(),new EnderChestOpenSound());
	}

	/**
	 * @param string|Permission $name
	 *
	 * @return bool
	 */
	public function hasPermission(Permission|string $name): bool
	{
		if (in_array($name, $this->permissions)) {
			return true;
		}
		if (in_array($name, $this->getRank()->getPermissions())) {
			return true;
		}
		return false;
	}

	/**
	 * @param string $permission
	 */
	public function addPermission(string $permission): void
	{
		$this->permissions[] = $permission;
		$this->permissions = array_unique($this->permissions);
		$xuid = $this->getPlayer()->getXuid();
		$permissions = implode(",", $this->permissions);
		Database::queryAsync("UPDATE players SET permissions = ? WHERE xuid = ?", "ss", [$permissions, $xuid]);
	}

	/**
	 * @return int
	 */
	public function getSouls(): int
	{
		if (is_null($this->souls)) {
			return 0;
		}
		return $this->souls;
	}

	/**
	 * @param int $amount
	 */
	public function addSouls(int $amount): void
	{
		$this->souls += $amount;
	}

	/**
	 * @param int $souls
	 */
	public function setSouls(int $souls): void{
		$this->souls = $souls;
	}

	public function setBalance(int $amount): void
	{
		$this->balance = $amount;
	}

	/**
	 * @return int
	 */
	public function getJackPotEarnings(): int
	{
		return $this->jackpotEarnings;
	}

	public function increaseEarnings(int $amount): void
	{
		$this->jackpotEarnings += $amount;
	}

	public function increaseWins($amount = 1): void
	{
		$this->jackpotWins += $amount;
	}

	/**
	 * @return int
	 */
	public function getJackPotWins(): int
	{
		return $this->jackpotWins;
	}

	public function addBalance(int $amount): void
	{
		$this->balance += $amount;
	}

	public function getReclaim(): int
	{
		return $this->reclaim;
	}

	public function setReclaimed(int $option = 1): void
	{
		$this->reclaim = $option;
	}

	public function getRank(): ?int
	{
		return $this->rank;
	}

	public function getBalance(): int
	{
		if (is_null($this->balance)) {
			return 0;
		}
		return $this->balance;
	}

	public function addGoldenApplesEaten(int $amount = 1): void{
		$this->goldenApplesEaten+= $amount;
	}

	public function setGoldenApplesEaten(int $amount): void{
		$this->goldenApplesEaten = $amount;
	}

	public function getGoldenApplesEaten(): int{
		return $this->goldenApplesEaten;
	}
	/**
	 * @return Faction|null
	 */
	public function getFaction(): ?Faction
	{
		return $this->faction;
	}

	/**
	 * @param Faction|null $faction
	 */
	public function setFaction(?Faction $faction): void
	{
		$this->faction = $faction;
		$faction = $faction instanceof Faction ? $faction->getName() : null;
		$xuid = $this->getPlayer()->getXuid();
		Database::queryAsync("UPDATE players SET faction = ? WHERE xuid = ?", "ss", [$faction, $xuid]);
	}

	/**
	 * @return int|null
	 */
	public function getFactionRole(): ?int
	{
		return $this->factionRole;
	}

	/**
	 * @return string
	 */
	public function getFactionRoleToString(): string
	{
		switch ($this->factionRole) {
			case Faction::MEMBER:
				return "*";
			case Faction::OFFICER:
				return "**";
			case Faction::LEADER:
				return "***";
			case Faction::RECRUIT:
			default:
				return "";
		}
	}

	/**
	 * @param int|null $role
	 */
	public function setFactionRole(?int $role): void
	{
		$this->factionRole = $role;
		$xuid = $this->getPlayer()->getXuid();
		Database::queryAsync("UPDATE players SET factionRole = ? WHERE xuid = ?", "ss", [$role, $xuid]);
	}

	/**
	 * Checks & Updates Players Sets
	 */
	public function checkSets(): void
	{
		$player = $this->getPlayer();
		$this->applyBuffs();
		if (!$player->isOnline()) {
			if (isset($this->lifestealmask[$player->getName()])) {
				Loader::getInstance()->getLogger()->info("[DEBUG] Removed array from " . $player->getName());
				unset($this->lifestealmask[$player->getName()]);
			}
		}
		if (!isset($this->lifestealmask[$player->getName()])) {
			if ($player->getArmorInventory()->getHelmet()->getNamedTag()->getString("lifesteal_mask", "") !== "") {
				$player->sendMessage("§r§2§lGRINDER MASK BONUS");
				$player->sendMessage("§r§2§l* §r§2Immunity to Fire & Lava");
				$player->sendMessage("§r§2§l* §r§235% Increased Chance Finding Relics & Meteors");
				$player->sendMessage("§r§2§l* §r§225% Chance to find SlotBot Scraps");
				$player->sendMessage("§r§2§l* §r§2No Hunger loss");
				$player->sendMessage("§r§2§l* §r§2Permanent Haste LVL: (III)");
				$player->sendMessage("§r§2§l* §r§2Permanent NightVision LVL: (IV)");
				Loader::playSound($player,"mob.irongolem.hit");
				$this->lifestealmask[$player->getName()] = $player;
			}
		}
		if (isset($this->lifestealmask[$player->getName()])) {
			if ($player->getArmorInventory()->getHelmet()->getNamedTag()->getString("lifesteal_mask", "") === "") {
				$player->sendMessage("§r§2§lGRINDER MASK");
				$player->sendMessage("§r§2§l- §r§2Night Vision LVL: (III)");
				$player->sendMessage("§r§2§l- §r§2Haste LVL: (III)");
				$player->sendMessage("§r§2§l- §r§2Mask Buffs & Percentages.");
				$player->sendMessage("§r§7You unequipped the Grinder Mask.");
				$player->getEffects()->remove(VanillaEffects::NIGHT_VISION());
				$player->getEffects()->remove(VanillaEffects::HASTE());
				Loader::playSound($player,"mob.cat.meow");
				if($player->getHealth() >= 10){
					$player->getEffects()->add(new EffectInstance(VanillaEffects::INSTANT_DAMAGE(),1,1));

				}
				unset($this->lifestealmask[$player->getName()]);
			}
		}
	}

	/**
	 * Applys Mask Bonuses & Effects
	 */
	public function applyBuffs(): void{
		$player = $this->getPlayer();
		if(isset($this->lifestealmask[$player->getName()])){
			if (!$player->getEffects()->has(VanillaEffects::NIGHT_VISION())) {
				$player->getEffects()->add(new EffectInstance(VanillaEffects::NIGHT_VISION(), 244444, 2));
				return;
			}
			if (!$player->getEffects()->has(VanillaEffects::HASTE())) {
				$player->getEffects()->add(new EffectInstance(VanillaEffects::HASTE(), 24444, 2));
				return;
			}
			if (!$player->getEffects()->has(VanillaEffects::FIRE_RESISTANCE())) {
				$player->getEffects()->add(new EffectInstance(VanillaEffects::FIRE_RESISTANCE(), 24444, 2));
				return;
			}
			if($player->getHungerManager()->getFood() <= 20){
				$player->getHungerManager()->setFood(20);
				return;
			}
			if($player->isOnFire()){
				$player->extinguish();
			}

		}
	}

	/**
	 * @return Player
	 */
	public function getPlayer(): Player{
		return $this->player;
	}
}