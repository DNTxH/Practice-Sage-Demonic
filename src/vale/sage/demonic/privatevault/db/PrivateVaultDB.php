<?php

namespace vale\sage\demonic\privatevault\db;

use vale\sage\demonic\Loader;
use vale\sage\demonic\privatevault\Vault;
use vale\sage\demonic\privatevault\VaultCache;
use pocketmine\item\Item;
use poggit\libasynql\DataConnector;
use poggit\libasynql\libasynql;
use SOFe\AwaitGenerator\Await;

class PrivateVaultDB implements IPrivateVaultDB{

    /**
     * @var DataConnector
     */
	private DataConnector $database;

    /**
     * @var array
     */
	private array $config = [
		// The database type. "sqlite" and "mysql" are supported.
		"type" => "sqlite",
		// Edit these settings only if you choose "sqlite".
		"sqlite" => [
			// The file name of the database in the plugin data folder.
			// You can also put an absolute path here.
			"file" => "data.sqlite"
		],
		// Edit these settings only if you choose "mysql".
		"mysql" => [
			"host" => "host",
			// Avoid using the "root" user for security reasons.
			"username" => "username",
			"password" => "password",
			"schema" => "schema"
		],
		// The maximum number of simultaneous SQL queries
		// Recommended: 1 for sqlite, 2 for MySQL. You may want to further increase this value if your MySQL connection is very slow.
		"worker-limit" => 1
	];

    /**
     * @param Loader $loader
     */
	public function __construct(private Loader $loader){
		$this->database = libasynql::create($this->loader, $this->config, ["sqlite" => "sqlite.sql", "mysql" => "mysql.sql"]);
		$this->database->executeGeneric(IPrivateVaultDB::QUERY_INIT);
	}

    /**
     * @param string $queryName
     * @param array $args
     * @return \Generator
     */
	public function awaitSelect(string $queryName, array $args = []) : \Generator{
		$this->database->executeSelect($queryName, $args, yield Await::RESOLVE);
		return yield AWAIT::ONCE;
	}

    /**
     * @param string $queryName
     * @param array $args
     * @return \Generator
     */
	public function awaitInsert(string $queryName, array $args = []) : \Generator{
		$this->database->executeInsert($queryName, $args, yield Await::RESOLVE);
		return yield AWAIT::ONCE;
	}

    /**
     * @param string $username
     * @param int $number
     * @return \Generator|Vault
     */
	public function loadVault(string $username, int $number){
		$vault = new Vault($username, $number, []);
		$vault->setLoading(true);
		VaultCache::addToCache($vault);

		$data = yield $this->awaitSelect(self::QUERY_LOAD, ["username" => $username, "number" => $number]);
		if(isset($data[0]["data"])){
			$items = [];
			if($this->config["type"] === "mysql"){
				foreach(json_decode($data[0]["data"], true) as $k => $v){
					$items[$k] = Item::jsonDeserialize($v);
				}
			}else{
				foreach(json_decode(hex2bin($data[0]["data"]), true) as $k => $v){
					$items[$k] = Item::jsonDeserialize($v);
				}
			}
			$vault->setItems($items);
		}

		$vault->setLoading(false);

		return $vault;
	}

    /**
     * @param Vault $vault
     * @return void
     */
	public function unloadVault(Vault $vault) : void{
		$vault->setUnloading(true);
		Await::f2c(function() use ($vault){
			yield $this->awaitInsert(self::QUERY_SAVE, ["username" => $vault->getusername(), "data" => json_encode($vault), "number" => $vault->getNumber()]);
			VaultCache::removeFromCache($vault);
			$vault->setUnloading(false);
		});
	}
}