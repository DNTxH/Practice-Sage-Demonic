<?php

declare(strict_types=1);

namespace vale\sage\demonic\provider;
use mysqli;
use vale\sage\demonic\Loader;
use vale\sage\demonic\provider\credentials\Credentials;
use vale\sage\demonic\provider\task\ReadResultsTask;
use vale\sage\demonic\provider\task\SavePlayerDataTask;
use vale\sage\demonic\provider\thread\MySQLThread;


class Database
{
	/** @var Credentials */
	private Credentials $credentials;

	/** @var mysqli */
	private mysqli $database;

	/** @var MySQLThread */
	private MySQLThread $thread;

	public function __construct(
		private Loader $core
	){
		$this->init();
	}

	public function init(): void
	{
		$this->credentials = new Credentials("158.51.123.53", "u10399_oYjwj1Q9Zc", "Sxvd75^dq^+zEue^N.pQc9Za", "s10399_genesis");
		$this->database = $this->credentials->createInstance();

		$this->database->query("
             CREATE TABLE IF NOT EXISTS players(xuid VARCHAR(36) PRIMARY KEY, username VARCHAR(16), faction VARCHAR(16) DEFAULT NULL, factionRole TINYINT DEFAULT NULL,
             rank TINYINT DEFAULT 0, permissions VARCHAR(255) DEFAULT 'hi', reclaim TINYINT DEFAULT 0,
             balance BIGINT DEFAULT 0, jackpotwins BIGINT DEFAULT 0, jackpotearnings BIGINT DEFAULT 0, souls BIGINT DEFAULT 0);");

		$this->database->query("
             CREATE TABLE IF NOT EXISTS factions(name VARCHAR(36) PRIMARY KEY, id BIGINT NOT NULL, creationDate VARCHAR(16) NOT NULL, 
                 leader TEXT NOT NULL, description TEXT DEFAULT NULL, access TEXT DEFAULT NULL, balance BIGINT DEFAULT 0,
             warps TEXT DEFAULT NULL, home TEXT DEFAULT NULL, banned TEXT DEFAULT NULL, allies TEXT DEFAULT NULL, enemies TEXT DEFAULT NULL,ranks TEXT DEFAULT NULL, strength BIGINT DEFAULT 0);");

		$this->thread = new MySQLThread($this->credentials);
		$this->thread->start(PTHREADS_INHERIT_INI | PTHREADS_INHERIT_CONSTANTS);
		Loader::getInstance()->getLogger()->info("INIT DATABASE!");
		$this->core->getScheduler()->scheduleRepeatingTask(new ReadResultsTask($this->thread), 1);
		$this->core->getScheduler()->scheduleRepeatingTask(new SavePlayerDataTask($this->core), 1000);
	}

	public function createNewThread(): MySQLThread
	{
		if (!$this->thread->isRunning()) {
			$this->thread = new MySQLThread($this->credentials);
			$this->thread->start(PTHREADS_INHERIT_INI | PTHREADS_INHERIT_CONSTANTS);
		}
		return $this->thread;
	}

	public function getDatabase(): mysqli
	{
		return $this->database;
	}

	public function getConnector(): MySQLThread
	{
		return $this->thread;
	}

	public function getCredentials(): Credentials
	{
		return $this->credentials;
	}
}