<?php

declare(strict_types=1);

namespace vale\sage\demonic\database;

use pocketmine\thread\Thread;
use Threaded;

abstract class DatabaseThread extends Thread {

    /** @var string */
    protected string $host;

    /** @var string */
    protected string $username;

    /** @var string */
    protected string $password;

    /** @var string */
    protected string $database;

    /** @var Threaded */
    protected $queryQueue;

    /** @var int */
    protected $currentQueryId = 0;

    /** @var bool */
    protected $isRunning = false;

    /**
     * DatabaseThread constructor.
     */
    public function __construct() {
        $this->host = Database::$host;
        $this->username = Database::$user;
        $this->password = Database::$password;
        $this->database = Database::$database;
        $this->queryQueue = new Threaded();

        $this->start();
    }

    /**
     * @param int $options
     * @return bool
     */
    public function start($options = PTHREADS_INHERIT_NONE) : bool {
        $this->isRunning = true;
        return parent::start($options);
    }

    public function quit() : void {
        $this->isRunning = false;

        while (($query = $this->queryQueue->shift()) !== null) {
            $query = igbinary_unserialize($query);
            $stmt = Database::getDatabase()->prepare($query["query"]);

            if($query["types"] !== "") {
                $stmt->bind_param($query["types"], ...$query["params"]);
            }

            $stmt->execute();

            $closure = Database::getResultsClosure($query["id"]);

            if($closure) {
                $closure(($res = $stmt->get_result()) ? $res->fetch_all(MYSQLI_ASSOC) : []);
            }

            $stmt->free_result();
        }
        parent::quit();
    }

    /**
     * @param string $query
     * @param string $types
     * @param array $params
     * @return int
     */
    public function submitQuery(string $query, string $types, array $params) : int {
        $id = $this->currentQueryId++;
        $q = [
            "id" => $id,
            "query" => $query,
            "types" => $types,
            "params" => $params
        ];

        $this->queryQueue[] = igbinary_serialize($q);
        return $id;
    }
}