<?php

namespace App\Reporting\DB;

use PDO;
use PDOException;

class ConnectionManager
{
	/** @var Connection */
	private static $connection;

	private function __construct()
	{
	}

	/**
	 * @return Connection
	 */
	public static function getConnection()
	{
		if (self::$connection == null) {
			$connectionInfo = self::getConnectionInfo();
			$connection = self::createConnection($connectionInfo);
			self::$connection = $connection;
		}

		return self::$connection;
	}

	/**
	 * return ConnectionInfo
	 */
	private static function getConnectionInfo()
	{
		return new CIConnectionInfo();
	}

	/**
	 * @param ConnectionInfo $connectionInfo
	 * @return Connection
	 */
	private static function createConnection(ConnectionInfo $connectionInfo)
	{
		$pdo = self::connectPDO($connectionInfo->dsn(), $connectionInfo->credentials(), $connectionInfo->connectionOptions());
		return new Connection($pdo, $connectionInfo->dsn(), $connectionInfo->credentials(), $connectionInfo->connectionOptions());
	}

	/**
	 * @param Dsn $dsn
	 * @param Credentials $credentials
	 * @param ConnectionOptions $options
	 * @return PDO
	 */
	private static function connectPDO(Dsn $dsn, Credentials $credentials, ConnectionOptions $options)
	{
		try {
			return new PDO($dsn->dsnString(), $credentials->getUser(), $credentials->getPassword(), $options->getOptions());
		} catch (PDOException $e) {
			throw new PDOException($e->getMessage(), (int)$e->getCode());
		}
	}
}