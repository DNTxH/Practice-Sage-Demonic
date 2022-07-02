<?php

declare(strict_types = 1);

namespace vale\sage\demonic\factions;


use pocketmine\player\Player;
use pocketmine\world\Position;
use pocketmine\world\World;
use vale\sage\demonic\factions\command\FactionCommand;
use vale\sage\demonic\Loader;
use vale\sage\demonic\Utils;
use vale\sage\demonic\sessions\player\SessionPlayer;
use Ramsey\Uuid\Uuid;
use pocketmine\Server;

class FactionManager
{

	/** @var Loader */
	private $core;

	/** @var Faction[] */
	public $factions = [];

	private $home;

	public static $instance;

	public $claims = [];

	/**
	 * FactionManager constructor.
	 *
	 * @param Loader $core
	 */
	public function __construct(Loader $core)
	{
		self::$instance = $this;
		$this->core = $core;
		$this->init();
		Server::getInstance()->getCommandMap()->register("faction", new FactionCommand(Loader::getInstance(),"f"));
		#$core->getServer()->getPluginManager()->registerEvents(new FactionListener($core), $core);
	}

	public function init(): void
	{
		$stmt = $this->core->getMySQLProvider()->getDatabase()->prepare("SELECT id, creationDate, name, leader, description, access, balance, warps, home, banned, allies, enemies, ranks, strength FROM factions");
		$stmt->execute();
		$stmt->bind_result($id, $creationDate, $name, $leader, $description, $access, $balance, $warps, $home, $banned, $allies, $enemies, $ranks, $strength);
		while ($stmt->fetch()) {
			if(!is_null($warps) && !is_null($banned) && !is_null($enemies) && !is_null($allies) && !is_null($ranks) && !is_null($description)) {
				$warps = unserialize($warps);
				$banned = unserialize($banned);
				$enemies = unserialize($enemies);
				$allies = unserialize($allies);
				$ranks = unserialize($ranks);
				$description = unserialize($description);
				$strength = unserialize($strength);
				$ranks = unserialize($ranks);
				$home = unserialize($home);
				$this->home = Utils::strToPos($home);
			}
			$faction = new Faction($id, $creationDate, $name, $leader, $description, $access, $balance, $warps, $home, $banned, $allies, $enemies, $ranks, $strength);
			$this->factions[$name] = $faction;
		}
		$stmt->close();
	}

	/**
	 * @return Faction[]
	 */
	public function getFactions(): array
	{
		return $this->factions;
	}

	/**
	 * @param string $name
	 *
	 * @return Faction|null
	 */
	public function getFaction(string $name): ?Faction
	{
		return $this->factions[$name] ?? null;
	}

	public function createFaction(string $name, Player $creator): void {
		$id = Uuid::uuid4()->toString();
		while(isset($this->factions[$id])) {
			$id = Uuid::uuid4()->toString();
		}
		$session = Loader::getInstance()->getSessionManager()->getSession($creator);
		$leader = $creator->getName();
		$cDate = date("m-d-Y g:iA");
		$stmt = Loader::getInstance()->getMySQLProvider()->getDatabase()->prepare("INSERT INTO factions(id, creationDate, name, leader) VALUES(?, ?, ?, ?)");
		$stmt->bind_param("ssss", $id,$cDate, $name, $leader);
		$stmt->execute();
		$stmt->close();
		$faction = new Faction($id, $cDate, $name, $leader, "Default Desc :(", 0, 0, 100, [], [], [], [], 0);
		$this->factions[$name] = $faction;
		$session->setFaction($this->factions[$name]);
	}

	public function getClaims() : array {
		$res = Loader::getInstance()->getMySQLProvider()->getDatabase()->query("SELECT * FROM claim;");
		$a = [];

		while($row = $res->fetch_array(SQLITE3_ASSOC)){
			$a[] = $row;
		}
		$claims = [];
		foreach($a as $claim) {
			$claims[World::chunkHash($claim["x"], $claim["z"])] = $claim["faction"];
		}
		return $claims;
	}

	public static function getInstance(): self{
		return self::$instance;
	}

	/**
	 * @param int $amount
	 */
	public static function addFactionCreations(int $amount = 1)
	{
		$config = Loader::getInstance()->getConfig();
		$config->set("faction_creations", $config->get("faction_creations") + $amount);
		$config->save();
	}
	public function getFactionByChunk(int $x, int $z) : ?string {
		return $this->claims[World::chunkHash($x, $z)] ?? null;
	}
}