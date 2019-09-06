<?php

namespace App\Reporting\DB;

class Dsn
{
	private $driver;

	private $host;

	private $database;

	private $charset;

	private $port;

	private $unix_socket;

	public static function mysql($host, $database, $charset = null, $port = null, $unix_socket = null)
	{
		return new self('mysql', $host, $database, $charset, $port, $unix_socket);
	}

	public static function mysqlLocal($database, $charset = null, $port = null, $unix_socket = null)
	{
		return new self('mysql', 'localhost', $database, $charset, $port, $unix_socket);
	}

	public function __construct($driver, $host, $database, $charset = null, $port = null, $unix_socket = null)
	{
		$this->driver = $driver;
		$this->host = $host;
		$this->database = $database;
		$this->charset = $charset;
		$this->port = $port;
		$this->unix_socket = $unix_socket;
	}

	public function __toString()
	{
		return $this->driver.':'.$this->stringifiedParams();
	}

	public function dsnString()
	{
		return $this->__toString();
	}

	private function stringifiedParams()
	{
		$keyValueStrings = [];
		foreach ($this->params() as $param => $paramValue) {
			$keyValueStrings[] = $param.'='.$paramValue;
		}

		return implode(';', $keyValueStrings);
	}

	private function params()
	{
		$params = [
			'host' => $this->host,
			'dbname' => $this->database,
		];

		if ($this->charset) {
			$params['charset'] = $this->charset;
		}
		if ($this->port) {
			$params['port'] = $this->port;
		}
		if ($this->unix_socket) {
			$params['unix_socket'] = $this->unix_socket;
		}

		return $params;
	}
}