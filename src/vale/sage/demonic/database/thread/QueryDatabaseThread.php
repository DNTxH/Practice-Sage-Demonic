<?php

declare(strict_types = 1);

namespace vale\sage\demonic\database\thread;

use vale\sage\demonic\database\DatabaseThread;

class QueryDatabaseThread extends DatabaseThread {

    /** @var \Threaded */
    private $resultQueue;

    /**
     * QueryDatabaseThread constructor.
     */
    public function __construct() {
        $this->resultQueue = new \Threaded();
        parent::__construct();
    }

    public function onRun() : void {
        begin:
        mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
        $database = new \mysqli($this->host, $this->username, $this->password, $this->database);
        mysqli_set_charset($database, 'utf8mb4');
        while ($this->isRunning || count($this->queryQueue) > 0) {
            if (!mysqli_ping($database)) {
                goto begin;
            }
            while (($query = $this->queryQueue->shift()) !== null) {
                $query = igbinary_unserialize($query);
                try {
                    $stmt = $database->prepare($query["query"]);
                    if ($query["types"] !== "") {
                        $stmt->bind_param($query["types"], ...$query["params"]);
                    }
                    $stmt->execute();

                    $this->resultQueue[] = igbinary_serialize([
                        "id" => $query["id"],
                        "result" => ($res = $stmt->get_result()) ? $res->fetch_all(MYSQLI_ASSOC) : [],
                    ]);

                    $stmt->free_result();
                } catch (\Exception $exception) {
                    echo "\n\n" .
                        "QUERY THREAD EXCEPTION\n" .
                        $exception->getMessage() . "\n" .
                        "Query: " . $query["query"] . "\n" .
                        "Types: " . $query["types"] . "\n" .
                        "Params: " . implode(", ", $query["params"]);
                }
            }

            usleep(50000);
        }
    }

    /**
     * @return array
     */
    public function getResults(): array {
        $results = [];
        while (($result = $this->resultQueue->shift()) !== null) {
            $results[] = igbinary_unserialize($result);
        }
        return $results;
    }

}