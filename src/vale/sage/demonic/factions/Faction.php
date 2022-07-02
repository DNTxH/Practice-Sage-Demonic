<?php

declare(strict_types = 1);

namespace vale\sage\demonic\factions;


use pocketmine\network\mcpe\protocol\LevelEventPacket;
use pocketmine\network\mcpe\protocol\LevelSoundEventPacket;
use pocketmine\network\mcpe\protocol\types\LevelSoundEvent;
use pocketmine\player\Player;
use pocketmine\Server;
use pocketmine\world\Position;
use pocketmine\world\sound\EndermanTeleportSound;
use pocketmine\world\World;
use vale\sage\demonic\Loader;
use vale\sage\demonic\Utils;

class Faction
{
	const MEMBER = 0;
	const OFFICER = 1;
	const LEADER = 2;
	const RECRUIT = 3;
	private $leader;
	private $creationDate;
	private $name;
	private $id;

	private $balance = 0;
	private $open = false;

	private $warps = [];
	private $home;

	private $banned = [];
	private $enemies = [];
	private $allies = [];
	private $strength;

	public $claims = [];

	private $ranks = [
		"recruit" => [],
		"member" => [],
		"officer" => [],
		"coleader" => [],
	];

	public static $instance;

	private $accessPlayers = [];
	private $players = [];

	public $requests = [];

	public function __construct(string $id, string $creationDate, string $name, string $leader, string $description, $open, int $balance, $warps, $banned, $allies, $enemies, $ranks, $strength)
	{
		self::$instance = $this;
		$this->id = $id;
		$this->creationDate = $creationDate;
		$this->name = $name;
		$this->leader = $leader;
		$this->description = $description;
		$this->open = (bool)$open;
		$this->balance = $balance;
		$this->warps = $warps;
		$this->banned = $banned;
		$this->allies = $allies;
		$this->enemies = $enemies;
		$this->ranks = $ranks;
		$this->strength = $strength;
	}

	public function getId(): string
	{
		return $this->id;
	}

	public function getCreationDate(): string
	{
		return $this->creationDate;
	}

	public function getStrength(): int
	{
		return $this->strength;
	}

	/**
	 * @return string
	 */
	public function getName(): string
	{
		return $this->name;
	}

	public function setName(string $name)
	{
		$this->name = $name; // ?
		$stmt = Loader::getInstance()->getMySQLProvider()->getDatabase()->prepare("UPDATE factions SET name = name WHERE id = ?");
		$stmt->bind_param("ss", $name, $this->id);
		$stmt->execute();
		$stmt->close();
	}

	public function getLeader(): string
	{
		return $this->leader;
	}

	public function setLeader(string $leader)
	{
		$this->leader = $leader;

		$stmt = Loader::getInstance()->getMySQLProvider()->getDatabase()->prepare("UPDATE factions SET leader = leader WHERE id = ?");
		$stmt->bind_param("ss", $leader, $this->id);
		$stmt->execute();
		$stmt->close();
	}

	public function getDescription(): string
	{
		return $this->description;
	}

	public function setDescription(string $description)
	{
		$this->description = $description;
		$desc = serialize($description);

		$stmt = Loader::getInstance()->getMySQLProvider()->getDatabase()->prepare("UPDATE factions SET description = description WHERE id = ?");
		$stmt->bind_param("ss", $desc, $this->id);
		$stmt->execute();
		$stmt->close();
	}

	public function isOpen(): bool
	{
		return $this->open;
	}

	public function setOpen(bool $open)
	{
		$this->open = (int)$open;
		$stmt = Loader::getInstance()->getMySQLProvider()->getDatabase()->prepare("UPDATE factions SET open = open WHERE id = ?");
		$stmt->bind_param("is", $open, $this->id);
		$stmt->execute();
		$stmt->close();
	}

	public function getBalance(): int
	{
		return $this->balance;
	}

	public function setBalance(int $amount)
	{
		$this->balance = $amount;
		$stmt = Loader::getInstance()->getMySQLProvider()->getDatabase()->prepare("UPDATE factions SET balance = balance WHERE id = ?");
		$stmt->bind_param("is", $amount, $this->id);
		$stmt->execute();
		$stmt->close();
	}

	public function addBalance(int $amount)
	{
		$this->setBalance($this->getBalance() + $amount);
	}

	public function reduceBalance(int $amount)
	{
		$this->setBalance($this->getBalance() - $amount);
	}

	public function getPower(): int
	{
		$power = 0;

		foreach ($this->getAllMembers() as $member) {
			$power = $power + $member->getPower();
		}
		return $power;
	}

	public function getWarps(): array
	{
		return $this->warps;
	}

	public function getWarp(string $name): ?Position
	{
		return isset($this->warps[$name]) ? Utils::strtoPos($this->warps[$name]) : null;
	}

	public function addWarp(Position $position, string $name)
	{
		$this->warps[$name] = Utils::postoString($position);
	}

	public function deleteWarp(string $name)
	{
		if (isset($this->warps[$name])) {
			unset($this->warps[$name]);
		}
	}

	public function getHome(): Position
	{
		return $this->home;
	}

	public function setHome(Position $position)
	{
		$home = Utils::postoString($position);
		$this->home = $home;
		$stmt = Loader::getInstance()->getMySQLProvider()->getDatabase()->prepare("UPDATE factions SET home = ? WHERE id = ?");
		$stmt->bind_param("ss", $home, $this->id);
		$stmt->execute();
		$stmt->close();
	}

	public function deleteHome()
	{
		$this->home = null;
	}

	public function getBanned(): array
	{
		return $this->banned;
	}

	public function isBanned(string $player): bool
	{
		return in_array($player, $this->banned, true);
	}

	public function setBanned(string $player, bool $value)
	{
		if ($value) {
			if (!$this->isBanned($player)) {
				$this->banned[] = $player;
			}
			$this->kick($player); //?
		} else {
			if ($this->isBanned($player)) {
				unset($this->banned[array_search($player, $this->banned)]);
			}
		}
	}

	public function getEnemies(): array
	{
		return $this->enemies;
	}

	public function setEnemy(Faction $faction)
	{
		if (!$this->isEnemy($faction)) {
			$this->enemies[] = $faction->getId();
		}
	}

	public function isEnemy(Faction $faction): bool
	{
		return in_array($faction->getId(), $this->enemies);
	}

	public function removeEnemy(Faction $faction)
	{
		if ($this->isEnemy($faction)) {
			unset($this->enemies[array_search($faction->getId(), $this->enemies)]);
		}
	}

	public function getAllies(): array
	{
		return $this->allies;
	}

	public function isAlly(Faction $faction): bool
	{
		return in_array($faction->getName(), $this->allies, true);
	}

	public function setAlly(Faction $faction)
	{
		if (!$this->isAlly($faction)) {
			$this->allies[] = $faction->getName();

			$this->setStrength($this->getStrength() + 100);

			$allies = serialize($this->allies);
			$stmt = Loader::getInstance()->getMySQLProvider()->getDatabase()->prepare("UPDATE factions SET allies = ? WHERE id = ?");
			$stmt->bind_param("ss", $allies, $this->id);
			$stmt->execute();
			$stmt->close();
		}
	}

	public function removeAlly(Faction $faction)
	{
		if ($this->isAlly($faction)) {
			unset($this->allies[array_search($faction->getName(), $this->allies, true)]);
			$this->setStrength($this->getStrength() - 100);

			$allies = serialize($this->allies);
			$stmt = Loader::getInstance()->getMySQLProvider()->getDatabase()->prepare("UPDATE factions SET allies = ? WHERE id = ?");
			$stmt->bind_param("ss", $allies, $this->id);
			$stmt->execute();
			$stmt->close();
		}
	}

	public function isMember(string $player): bool
	{
		return $this->getRank($player) !== null;
	}

	public function getAllMembers(): array
	{
		$all = [$this->getLeader()];

		foreach ($this->ranks as $rank => $players) {
			foreach ($players as $p) {
				$all[] = $p;
			}
		}
		return $all;
	}

	public function getOnlinePlayerCount(): int
	{
		return count($this->players);
	}

	public function getMemberCount(): int
	{
		return array_sum(array_map("count", $this->getRanks())) + 1;
	}

	public function addMember(string $player)
	{
		$this->setRank($player, "recruit");
		$this->players[] = Server::getInstance()->getPlayerExact($player);
		$this->setStrength($this->getStrength() + 100);
	}

	public function join(Player $player)
	{
		$this->players[] = $player;
	}

	public function leave(Player $player): void
	{
		if (in_array($player, $this->players, true)) {
			unset($this->players[array_search($player, $this->players, true)]);
		}
	}

	/**
	 * @return Player[]
	 */
	public function getOnlinePlayers(): array
	{
		$players = [];
		foreach (Server::getInstance()->getOnlinePlayers() as $player){
			$session = Loader::getInstance()->getSessionManager()->getSession($player);
			if($session->getFaction() === $this){
				$players[] = $player;
			}
		}
		return $players;
	}

	public function kick(string $player)
	{
		foreach ($this->ranks as $rank => $players) {
			if (in_array($player, $players, true)) {
				unset($this->ranks[$rank][array_search($player, $players, true)]);
				$stmt = Loader::getInstance()->getMySQLProvider()->getDatabase()->prepare("UPDATE factions SET ranks = ? WHERE id = ?");
				$stmt->bind_param("ss", $this->ranks, $this->id);
				$stmt->execute();
				$stmt->close();
			}
		}
		$session = Loader::getInstance()->getSessionManager()->getSession($player);
		$session->setFaction(null);
		$this->setStrength($this->getStrength() - 100);
	}

	public function getRanks(): array
	{
		return $this->ranks;
	}

	public function getRank(string $player): ?string
	{
		if ($this->leader === $player) {
			return "leader";
		}
		foreach ($this->ranks as $rank => $players) {
			if (in_array($player, $players)) {
				return $rank;
			}
		}
		return null;
	}

	public function setRank(string $player, string $rank)
	{
		foreach ($this->ranks as $name => $players) {
			if ($name === $rank && in_array($player, $players, true)) {
				return;
			}
			if (in_array($player, $players, true)) unset($this->ranks[$name][array_search($player, $players)]);
		}
		$this->ranks[$rank][] = $player;

		$stmt = Loader::getInstance()->getMySQLProvider()->getDatabase()->prepare("UPDATE factions SET ranks = ? WHERE id = ?");
		$stmt->bind_param("ss", $this->ranks, $this->id);
		$stmt->execute();
		$stmt->close();
	}

	public function get0Players(): array
	{
		return $this->accessPlayers;
	}

	public function isAccessPlayer(string $player): bool
	{
		return in_array($player, $this->accessPlayers, true);
	}

	public function addAccessPlayer(string $player)
	{
		if (!$this->isAccessPlayer($player)) {
			$this->accessPlayers[] = $player;

			$stmt = Loader::getInstance()->getMySQLProvider()->getDatabase()->prepare("UPDATE factions SET accessPlayers = ? WHERE id = ?");
			$stmt->bind_param("ss", $this->accessPlayers, $this->id);
			$stmt->execute();
			$stmt->close();
		}
	}

	public function removeAccessPlayer(string $player)
	{
		if ($this->isAccessPlayer($player)) {
			unset($this->accessPlayers[array_search($player, $this->accessPlayers, true)]);

			$stmt = Loader::getInstance()->getMySQLProvider()->getDatabase()->prepare("UPDATE factions SET accessPlayers = ? WHERE id = ?");
			$stmt->bind_param("ss", $this->accessPlayers, $this->id);
			$stmt->execute();
			$stmt->close();
		}
	}

	public function hasPermissionByPlayer(string $player, string $permission): bool
	{
		$rank = $this->getRank($player);

		if ($rank === "coleader" || $this->getLeader() === $player) {
			return true;
		}
		return in_array($permission, constant("Altered\\faction\\type\\Perms::$rank"));
	}

	public function getChunkCount(): int
	{
		return count($this->getChunks());
	}

	public function getChunks(): array
	{
		return array_filter(FactionManager::getInstance()->claims, function (int $faction): bool {
			return $faction === $this->getId();
		});
	}

	public function overclaimChunk(int $x, int $z, Player $sender): bool
	{
		$fac = Loader::getInstance()->getFactionsManager()->getFaction(Loader::getInstance()->getFactionsManager()->getFactionByChunk($x, $z));

		if ($fac == null) {
			$sender->sendMessage("§cFaction is already free!");
			return false;
		}
		if ($fac->getStrength() - 2000 > $fac->getStrength()) {
			$sender->sendMessage("§cYou need to have 2000 more STR than the owning faction to overclaim! (your STR: §6" . $fac->getStrength() . "§c, their STR: §6" . $oldFac->getStrength() . "§c)");
			return false;
		}
		$amt = $this->getChunkCount() * 100 + 1000;

		if ($this->getStrength() < $amt) {
			$sender->sendMessage("§cFaction STR not enough to claim this chunk! (Needed amount: $amt)");
			return false;
		}
		$this->setStrength($this->getStrength() - $amt);
		$this->claimChunk($x, $z);

		if ($sender) {
			$sender->sendMessage("§aChunk $x, $z overclaimed!");
			Loader::playSound($sender, ""); //todo
		}
		return false;
	}

	public function claimChunk(int $x, int $z)
	{
		$hash = World::chunkHash($x, $z);

		if (isset($this->claims[$hash])) {
			$id = $this->getId();
			$stmt = Loader::getInstance()->getMySQLProvider()->getDatabase()->prepare("DELETE FROM claims WHERE fac = ? AND x = ? AND z = ?");
			$stmt->bind_param("sii", $id, $x, $z);
			$stmt->execute();
			$stmt->close();

			unset(FactionManager::getInstance()->claims[World::chunkHash($x, $z)]);
		}
		FactionManager::getInstance()->claims[$hash] = $this->getId();

		$stmt = Loader::getInstance()->getMySQLProvider()->getDatabase()->prepare("REPLACE INTO claims WHERE fac = ? AND x = ? AND z = ?");
		$stmt->bind_param("sii", $id, $x, $z);
		$stmt->execute();
		$stmt->close();
	}

	public function loseChunk(int $x, int $z)
	{
		$id = $this->getId();
		$stmt = Loader::getInstance()->getMySQLProvider()->getDatabase()->prepare("DELETE FROM claims WHERE fac = ? AND x = ? AND z = ?");
		$stmt->bind_param("sii", $id, $x, $z);
		$stmt->execute();
		$stmt->close();

		unset(FactionManager::getInstance()->claims[World::chunkHash($x, $z)]);
	}

	public function tryClaimChunk(int $chunkX, int $chunkZ, ?Player $sender = null, bool $overclaim = false): bool
	{
		$amt = 10;

		if ($this->getStrength() < $amt) {
			if ($sender) $sender->sendMessage("§cFaction STR not enough to claim this chunk! (Needed amount: $amt)");
			return false;
		}
		if ($overclaim or FactionManager::getInstance()->getFactionByChunk($chunkX, $chunkZ) !== null) {
			$fac = FactionManager::getInstance()->getFaction(FactionManager::getInstance()->getFactionByChunk($chunkX, $chunkZ));

			if ($fac == null) {
				return false; //wtf
			}
			if ($sender) {
				$sender->sendMessage("§cChunk is already claimed by " . $fac->getName() . "!");
			}
			return false;
		}
		$this->setStrength($this->getStrength() - $amt);
		$this->claimChunk($chunkX, $chunkZ);

		$sender?->sendMessage("§aChunk $chunkX, $chunkZ claimed!");
		return true;
	}

	public function unclaimAll()
	{
		foreach ($this->getChunks() as $hash) {
			unset(FactionManager::getInstance()->claims[$hash]);

			$stmt = Loader::getInstance()->getMySQLProvider()->getDatabase()->prepare("DELETE * FROM claims WHERE fac = ?");
			$stmt->bind_param("s", $this->id);
			$stmt->execute();
			$stmt->close();
		}
	}

	public function getValue()
	{

	}

	public function setStrength(int $amount): void
	{
		$this->strength = $amount;
	}

	public function remove(): void
	{
		foreach ($this->getAllies() as $allyId) {
			$allyFaction = $this->getFaction($allyId);

			if ($allyFaction instanceof Faction) {
				$allyFaction->removeAlly($allyId);
			}
		}
		foreach ($this->getEnemies() as $enemyId) {
			$enemyFac = $this->getFaction($enemyId);

			if ($enemyFac instanceof Faction) {
				$enemyFac->removeEnemy($enemyId);
			}
		}
		$id = $this->getId();
		unset(FactionManager::getInstance()->factions[$id]);
		Loader::getInstance()->getMySQLProvider()->getDatabase()->prepare("DELETE FROM faction WHERE id = $id;");
		self::getInstance()->unclaimAll($id);
	}

	public function getFaction(string $name): ?Faction
	{
		$fac = Faction::getInstance()->getFaction($name);
		return $fac;
	}

	public static function getInstance(): self
	{
		return self::$instance;
	}

	public function announce(string $message): void
	{
		foreach ($this->getOnlinePlayers() as $player) {
			$player->sendMessage($message);
		}
	}
}