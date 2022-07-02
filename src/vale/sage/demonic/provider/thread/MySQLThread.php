<?php

namespace vale\sage\demonic\provider\thread;
use pocketmine\thread\Thread;
use Threaded;
use Exception;
use vale\sage\demonic\provider\cache\CallableCache;
use vale\sage\demonic\provider\credentials\Credentials;

class MySQLThread extends Thread
{
	/** @var bool */
	private bool $running = true;

	/** @var Threaded */
	private Threaded $queries;

	/** @var Threaded */
	private Threaded $results;

	/** @var int */
	private int $queryId = 0;

	/** @var string */
	private string $credentials;

	/**
	 * MySQLThread constructor.
	 *
	 * @param Credentials $credentials
	 */
	public function __construct(Credentials $credentials)
	{
		$this->queries = new Threaded();
		$this->results = new Threaded();
		$this->credentials = serialize($credentials);
	}

	/**
	 * @throws Exception
	 */
	public function onRun(): void
	{
		/** @var Credentials $credentials */
		$credentials = unserialize($this->credentials);
		$mysqli = $credentials->createInstance();
		while ($this->running) {
			while (($query = $this->queries->shift()) !== null) {
				$query = igbinary_unserialize($query);
				switch ($query["type"]) {
					case "select":
						$stmt = $mysqli->prepare($query["query"]);
						if (!$stmt->bind_param($query["types"], ...$query["params"])) {
							throw new Exception("MySQL error: " . $stmt->error . ($query["query"] === null ? "" : (", for query {$query["query"]} | " . json_encode($query["params"]))));
						}
						if (!$stmt->execute()) {
							throw new Exception("MySQL error: " . $stmt->error . ($query["query"] === null ? "" : (", for query {$query["query"]} | " . json_encode($query["params"]))));
						}
						$results = [];
						$res = $stmt->get_result();
						while ($row = $res->fetch_assoc()) {
							$results[] = $row;
						}
						$stmt->close();
						$this->results[] = igbinary_serialize([
							"id" => $query["id"],
							"result" => $results
						]);
						break;
					case "selectQuery":
						$result = $mysqli->query($query["query"]);
						if (!$result) {
							throw new Exception("MySQL error: " . $mysqli->error . ($query["query"] === null ? "" : (", for query {$query["query"]} | " . json_encode($query["params"]))));
						}
						$results = [];
						while ($row = $result->fetch_assoc()) {
							$results[] = $row;
						}
						$this->results[] = igbinary_serialize([
							"id" => $query["id"],
							"result" => $results
						]);
						break;
					case "update":
						$stmt = $mysqli->prepare($query["query"]);
						if (!$stmt->bind_param($query["types"], ...$query["params"])) {
							throw new Exception("MySQL error: " . $stmt->error . ($query["query"] === null ? "" : (", for query {$query["query"]} | " . json_encode($query["params"]))));
						}
						if (!$stmt->execute()) {
							throw new Exception("MySQL error: " . $stmt->error . ($query["query"] === null ? "" : (", for query {$query["query"]} | " . json_encode($query["params"]))));
						}
						$stmt->close();
						break;
				}
			}
			$this->sleep();
		}
	}

	public function sleep(): void
	{
		$this->synchronized(function (): void {
			if ($this->running) {
				$this->wait();
			}
		});
	}

	/**
	 * @param string $query
	 * @param string $types
	 * @param array $params
	 * @param callable|null $callable
	 */
	public function executeSelect(string $query, string $types, array $params, ?callable $callable = null): void
	{
		$query = [
			"query" => $query,
			"type" => "select",
			"types" => $types,
			"params" => $params,
			"id" => ++$this->queryId
		];
		CallableCache::$callables[$query["id"]] = $callable;
		$this->queries[] = igbinary_serialize($query);
		$this->synchronized(function (): void {
			$this->notify();
		});
	}

	/**
	 * @param string $query
	 * @param callable|null $callable
	 */
	public function executeSelectQuery(string $query, ?callable $callable = null): void
	{
		$query = [
			"query" => $query,
			"type" => "selectQuery",
			"id" => ++$this->queryId
		];
		CallableCache::$callables[$query["id"]] = $callable;
		$this->queries[] = igbinary_serialize($query);
		$this->synchronized(function (): void {
			$this->notify();
		});
	}

	/**
	 * @param string $query
	 * @param string $types
	 * @param array $params
	 */
	public function executeUpdate(string $query, string $types, array $params): void
	{
		$query = [
			"query" => $query,
			"type" => "update",
			"types" => $types,
			"params" => $params,
			"id" => ++$this->queryId
		];
		$this->queries[] = igbinary_serialize($query);
		$this->synchronized(function (): void {
			$this->notify();
		});
	}

	public function checkResults(): void
	{
		while (($result = $this->results->shift()) !== null) {
			$result = igbinary_unserialize($result);
			$callable = CallableCache::$callables[$result["id"]];
			$callable($result["result"]);
		}
	}

	public function quit(): void
	{
		$this->running = false;
		parent::quit();
	}
}