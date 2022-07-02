<?php

declare(strict_types = 1);

namespace vale\sage\demonic\database;

use vale\sage\demonic\database\thread\InsertDatabaseThread;
use vale\sage\demonic\database\thread\QueryDatabaseThread;
use vale\sage\demonic\Loader;
use pocketmine\scheduler\ClosureTask;
use mysqli;
use Closure;

// i apologize to whoever has to maintain this, I made this database to work with the genesis player class for levels and talents before
// it was merged with this core where another database is present, use either.
class Database {

    /** @var string */
    public static $host;

    /** @var string */
    public static $user;

    /** @var string */
    public static $password;

    /** @var string */
    public static $database;

    /** @var QueryDatabaseThread */
    private static $queryDatabaseThread;

    /** @var InsertDatabaseThread */
    private static $insertDatabaseThread;

    /** @var Closure */
    private static $results = [];

    /** @var mysqli */
    private static $db;

    public static function init() : void {
        self::$host = "158.51.123.53";
        self::$user = "u10399_oYjwj1Q9Zc";
        self::$password = "Sxvd75^dq^+zEue^N.pQc9Za";
        self::$database = "s10399_genesis";

        self::$queryDatabaseThread = new QueryDatabaseThread();
        self::$insertDatabaseThread = new InsertDatabaseThread();

        Loader::getInstance()->getScheduler()->scheduleDelayedRepeatingTask(new ClosureTask(static function() : void {
            foreach (Database::getQueryDatabaseThread()->getResults() as $result) {
                $callable = Database::getResultsClosure($result["id"]);
                if($callable !== null) {
                    $callable($result["result"]);
                }
            }
        }), 3, 3);

        self::$db = new mysqli(self::$host, self::$user, self::$password, self::$database, 3306);

        self::$db->query("CREATE TABLE IF NOT EXISTS core_players(
            xuid BINARY(16) PRIMARY KEY,
            username VARCHAR(16) UNIQUE,
            level INT DEFAULT 1,
            experience INT DEFUAULT 0,
            talentPoints INT DEFAULT 1,
            dodgeTalentLevel INT DEFAULT 0,
            sellTalentLevel INT DEFAULT 0,
            xpTalentLevel INT DEFAULT 0,
            pvpOutgoingTalentLevel INT DEFAULT 0,
            pveTalentLevel INT DEFAULT 0
            pvpIncomingTalentLevel INT DEFAULT 0,
            minersFortuneTalentLevel INT DEFAULT 0,
            luckyTalentLevel INT DEFAULT 0,
        );");
    }

    /**
     * @return mysqli
     */
    public static function getDatabase() : mysqli {
        return mysqli_ping(self::$db) ? self::$db : self::$db = new mysqli(self::$host, self::$user, self::$password, self::$database);
    }

    /**
     * @return QueryDatabaseThread
     */
    public static function getQueryDatabaseThread() : QueryDatabaseThread {
        return self::$queryDatabaseThread;
    }

    /**
     * @return InsertDatabaseThread
     */
    public static function getInsertDatabaseThread() : InsertDatabaseThread {
        return self::$insertDatabaseThread;
    }

    /**
     * @param int $id
     * @return Closure|null
     */
    public static function getResultsClosure(int $id) :?Closure {
        return self::$results[$id] ?? null;
    }

    /**
     * @param string $query
     * @param string $types
     * @param array $params
     * @return array
     */
    public static function querySync(string $query, string $types, array $params = []) : array {
        try {
            $stmt = self::getDatabase()->prepare($query);
            if($types !== "") {
                $stmt->bind_param($types, ...$params);
            }
            if($stmt->execute()) {
                $retval = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
                $stmt->free_result();
                return is_array($retval) ? $retval : [];
            }
        } catch (\Exception $exception) {
            Loader::getInstance()->getLogger()->logException($exception);
        }
        return [];
    }

    /**
     * @param string $query
     * @param string $types
     * @param array $params
     * @param Closure|null $onComplete
     */
    public static function queryAsync(string $query, string $types = "", array $params = [], ?Closure $onComplete = null) {
        if($onComplete !== null || str_contains($query, "SELECT") !== false) {
            $id = self::getQueryDatabaseThread()->submitQuery($query, $types, $params);
            self::$results[$id] = $onComplete;
        } else {
            self::getInsertDatabaseThread()->submitQuery($query, $types, $params);
        }
    }

    public static function closeDatabase() : void {
        self::$db->close();
        self::$db = null;
    }
}