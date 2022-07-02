<?php

declare(strict_types=1);

namespace vale\sage\demonic\provider\credentials;

use mysqli;

class Credentials
{

	/** @var string */
	private string $host;

	/** @var string */
	private string $username;

	/** @var string */
	private string $password;

	/** @var string */
	private string $database;

	/** @var int */
	private int $port;

	/**
	 * MySQLCredentials constructor.
	 *
	 * @param string $host
	 * @param string $username
	 * @param string $password
	 * @param string $database
	 * @param int $port
	 */
	public function __construct(string $host, string $username, string $password, string $database, int $port = 3306)
	{
		$this->host = $host;
		$this->username = $username;
		$this->password = $password;
		$this->database = $database;
		$this->port = $port;
	}

	/**
	 * @return string
	 */
	public function getUsername(): string
	{
		return $this->username;
	}

	/**
	 * @return int
	 */
	public function getPort(): int
	{
		return $this->port;
	}

	/**
	 * @return mysqli
	 */
	public function createInstance(): mysqli
	{
		return new mysqli($this->host, $this->username, $this->password, $this->database, $this->port);
	}
}