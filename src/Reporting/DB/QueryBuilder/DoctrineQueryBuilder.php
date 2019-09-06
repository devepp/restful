<?php

namespace App\Reporting\DB\QueryBuilder;

use Doctrine\DBAL\DriverManager;
use Doctrine\DBAL\Query\Expression\CompositeExpression;
use App\Reporting\DB\ConnectionManager;
use App\Reporting\DB\Query;
use App\Reporting\DB\QueryBuilder as QBInterface;
use Doctrine\DBAL\Query\QueryBuilder;
use App\Reporting\DB\QueryBuilder\QueryParts\TableExpression;


class DoctrineQueryBuilder implements QBInterface
{
	/** @var QueryBuilder */
	private $qb;

	/**
	 * DoctrineQueryBuilder constructor.
	 * @param QueryBuilder $qb
	 */
	private function __construct(QueryBuilder $qb)
	{
		$this->qb = $qb;
	}

	public static function selectBuilder($tableExpression)
	{
		$tableExpression = new TableExpression($tableExpression);
		$qb = self::getDoctrineQueryBuilder();

		return new self($qb->from($tableExpression->getTable(), $tableExpression->getAlias()));
	}

	public static function updateBuilder($tableExpression)
	{
		$tableExpression = new TableExpression($tableExpression);
		$qb = self::getDoctrineQueryBuilder();

		return new self($qb->update($tableExpression->getTable(), $tableExpression->getAlias()));
	}

	public static function insertBuilder($tableExpression)
	{
		$tableExpression = new TableExpression($tableExpression);
		$qb = self::getDoctrineQueryBuilder();

		return new self($qb->insert($tableExpression->getTable()));
	}

	public static function deleteBuilder($tableExpression)
	{
		$tableExpression = new TableExpression($tableExpression);
		$qb = self::getDoctrineQueryBuilder();

		return new self($qb->delete($tableExpression->getTable(), $tableExpression->getAlias()));
	}

	public function getQuery()
	{
		return new Query($this->qb->getSQL(), $this->qb->getParameters());
	}

	public function select(...$fieldExpressions)
	{
		$this->qb->select($fieldExpressions);
		return $this;
	}

	public function addSelect(...$fieldExpressions)
	{
		$this->qb->addSlect($fieldExpressions);
		return $this;
	}

	public function from($tableExpression)
	{
		$tableExpression = new TableExpression($tableExpression);
		$this->qb->from($tableExpression->getTable(), $tableExpression->getAlias());
		return $this;
	}

	public function where($field, $operator, $value)
	{
		$whereExpression = $this->qb->expr()->comparison($field, $operator, $value);
		$this->qb->where($whereExpression);
		return $this;
	}

	public function whereRaw($whereString)
	{
		$this->qb->where(new CompositeExpression(CompositeExpression::TYPE_AND, $whereString));
		return $this;
	}

	public function andWhere($field, $operator, $value)
	{
		$whereExpression = $this->qb->expr()->comparison($field, $operator, $value);
		$this->qb->andWhere($whereExpression);
		return $this;
	}

	public function whereIn($field, $values)
	{
		$whereExpression = $this->qb->expr()->in($field, $values);
		$this->qb->where($whereExpression);
		return $this;
	}

	public function whereNull($field)
	{
		$whereExpression = $this->qb->expr()->isNull($field);
		$this->qb->where($whereExpression);
		return $this;
	}

	public function join($table, $on, $type = 'inner')
	{
		$fromAlias = $on;
		$toAlias = $on;
		if ($type == 'inner') {
			$this->qb->join($fromAlias, $table, $toAlias, $on);
		}
		return $this;
	}

	public function update($table = null)
	{
		$this->qb->update($table);
		return $this;
	}

	public function insert($table = null)
	{
		$this->qb->insert($table);
		return $this;
	}

	public function delete($table = null)
	{
		$this->qb->delete($table);
		return $this;
	}

	private static function getDoctrineQueryBuilder()
	{
		$ebaseConnection = ConnectionManager::getConnection();

		$doctrineConnection = DriverManager::getConnection(['pdo' => $ebaseConnection->getPDO()]);

		return $doctrineConnection->createQueryBuilder();
	}

}