<?php

namespace App\Reporting\DB;

use PDO;

class Connection implements ConnectionInterface
{
	/** @var PDO */
	private $pdo;

	/** @var Dsn */
	private $dsn;

	/** @var Credentials */
	private $credentials;

	/** @var ConnectionOptions */
	private $options;

	/**
	 * Connection constructor.
	 * @param PDO $pdo
	 * @param Dsn $dsn
	 * @param Credentials $credentials
	 * @param ConnectionOptions $options
	 */
	public function __construct(PDO $pdo, Dsn $dsn, Credentials $credentials, ConnectionOptions $options)
	{
		$this->pdo = $pdo;
		$this->dsn = $dsn;
		$this->credentials = $credentials;
		$this->options = $options;
	}

	public function getPDO()
	{
		return $this->pdo;
	}

	/**
	 * @param Query $query
	 * @return Result
	 */
	public function execute(Query $query)
	{
		$statement = $this->pdo->prepare($query->getStatement());
		$statement->execute($query->getParameters());
		return new Result($statement);
	}

	public function beginTransaction()
	{
		return $this->pdo->beginTransaction();
	}

	public function commit()
	{
		return $this->pdo->commit();
	}

	public function rollback()
	{
		return $this->pdo->commit();
	}

	public function lastInsertId()
	{
		return $this->pdo->lastInsertId();
	}

	public function selectFrom($tableExpression)
	{
		return Query::selectQueryBuilder($tableExpression);
	}

	public function update($tableExpression)
	{
		return Query::selectQueryBuilder($tableExpression);
	}

	public function insertInto($tableExpression)
	{
		return Query::selectQueryBuilder($tableExpression);
	}

	public function deleteFrom($tableExpression)
	{
		return Query::selectQueryBuilder($tableExpression);
	}
}