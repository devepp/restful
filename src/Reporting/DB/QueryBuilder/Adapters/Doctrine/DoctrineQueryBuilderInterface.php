<?php

namespace App\Reporting\DB\QueryBuilder\Adapters\Doctrine;

use Doctrine\DBAL\DriverManager;
use Doctrine\DBAL\Query\Expression\CompositeExpression;
use App\Reporting\DB\ConnectionManager;
use App\Reporting\DB\Query;
use Doctrine\DBAL\Query\QueryBuilder;
use App\Reporting\DB\QueryBuilder\QueryParts\Join;

class DoctrineQueryBuilder implements QueryBuilderInterface, SelectQueryBuilderInterface
{
	/** @var QueryBuilder */
	private $qb;

	/**
	 * DoctrineQueryBuilderInterface constructor.
	 * @param QueryBuilder $qb
	 */
	public function __construct(QueryBuilder $qb, $tableExpression)
	{
		$this->qb = $qb;
		$this->qb->from($tableExpression);
	}

	public function getStatementExpression()
	{
		return $this->qb->getSQL();
	}

	public function getParameters()
	{
		return $this->qb->getParameters();
	}

	public function __toString()
	{
		return $this->getStatementExpression();
	}

	public function getQuery()
	{
		return new Query($this->getStatementExpression(), $this->getParameters());
	}

	public function select(...$fieldExpressions)
	{
		$this->qb->addSelect($fieldExpressions);
		return $this;
	}

	public function selectSubQuery(SelectQueryBuilderInterface $queryBuilder, $alias)
	{
		$this->qb->addSelect('('.$queryBuilder->getStatementExpression().') '.$alias);
	}

	public function where($field, $operator, $value)
	{
		$whereExpression = $this->qb->expr()->comparison($field, $operator, $this->qb->createPositionalParameter($value));
		$this->qb->andWhere($whereExpression);
		return $this;

		$this->qb->createPositionalParameter($value);
	}

	public function whereRaw($whereString)
	{
		$this->qb->andWhere(new CompositeExpression(CompositeExpression::TYPE_AND, $whereString));
		return $this;
	}

	public function orWhere($field, $operator, $value)
	{
		$whereExpression = $this->qb->expr()->comparison($field, $operator, $this->qb->createPositionalParameter($value));
		$this->qb->orWhere($whereExpression);
		return $this;
	}

	public function whereIn($field, $values)
	{
		$whereExpression = $this->qb->expr()->in($field, $values);
		$this->qb->andWhere($whereExpression);
		return $this;
	}

	public function whereNotIn($field, $values)
	{
		$whereExpression = $this->qb->expr()->notIn($field, $values);
		$this->qb->andWhere($whereExpression);
		return $this;
	}

	public function whereNull($field)
	{
		$whereExpression = $this->qb->expr()->isNull($field);
		$this->qb->andWhere($whereExpression);
		return $this;
	}

	public function whereNotNull($field)
	{
		$whereExpression = $this->qb->expr()->isNotNull($field);
		$this->qb->andWhere($whereExpression);
		return $this;
	}

	public function whereExists(SelectQueryBuilderInterface $selectQueryBuilder)
	{
		$this->qb->andWhere('EXISTS('.$selectQueryBuilder->getStatementExpression().')');
		return $this;
	}

	public function whereNotExists(SelectQueryBuilderInterface $selectQueryBuilder)
	{
		$this->qb->andWhere('EXISTS('.$selectQueryBuilder->getStatementExpression().')');
		return $this;
	}

	public function join($table, $on, $type = Join::INNER)
	{
		$fromAlias = $this->parseFrom($on);
		$toAlias = $this->parseFrom($on, $fromAlias);

		if (strtoupper($type) === Join::INNER) {
			$this->qb->innerJoin($fromAlias, $table, $toAlias, $on);
			return $this;
		}
		if (strtoupper($type) === Join::LEFT) {
			$this->qb->leftJoin($fromAlias, $table, $toAlias, $on);
			return $this;
		}
		if (strtoupper($type) === Join::RIGHT) {
			$this->qb->rightJoin($fromAlias, $table, $toAlias, $on);
			return $this;
		}

		throw new \InvalidArgumentException('$type must one of the following "'.Join::INNER.'", "'.Join::LEFT.'", "'.Join::RIGHT.'". "'.$type.'" was given.');
	}

	public function joinSubQuery(SelectQueryBuilderInterface $subQuery, $alias, $on, $type = 'INNER')
	{
		$fromAlias = $this->parseFrom($on, $alias);

		if (strtoupper($type) === Join::INNER) {
			$this->qb->innerJoin($fromAlias, '('.$subQuery->getStatementExpression().')', $alias, $on);
		}
		if (strtoupper($type) === Join::LEFT) {
			$this->qb->leftJoin($fromAlias, '('.$subQuery->getStatementExpression().')', $alias, $on);
			return $this;
		}
		if (strtoupper($type) === Join::RIGHT) {
			$this->qb->rightJoin($fromAlias, '('.$subQuery->getStatementExpression().')', $alias, $on);
			return $this;
		}

		throw new \InvalidArgumentException('$type must one of the following "'.Join::INNER.'", "'.Join::LEFT.'", "'.Join::RIGHT.'". "'.$type.'" was given.');
	}

	public function groupBy($field)
	{
		$this->qb->groupBy($field);
		return $this;
	}

	public function orderBy($sort, $direction = 'ASC')
	{
		$this->qb->orderBy($sort, $direction);
		return $this;
	}

	public function limit($limit, $offset = null)
	{
		$this->qb->setMaxResults($limit);
		if (isset($offset)) {
			$this->qb->setFirstResult($limit);
		}
		return $this;
	}

	public function subQuery($tableExpression)
	{
		$newQb = $this->qb->getConnection()->createQueryBuilder();

		return new self($newQb);
	}

	private static function getDoctrineQueryBuilder()
	{
		$ebaseConnection = ConnectionManager::getConnection();

		$doctrineConnection = DriverManager::getConnection(['pdo' => $ebaseConnection->getPDO()]);

		return $doctrineConnection->createQueryBuilder();
	}

	private function parseFrom($on, $alias = null)
	{
		$onSplitArray = explode(' ', $on);

		if (count($onSplitArray) != 3) {
			throw new \InvalidArgumentException('$on must contain 2 spaces. '.(count($onSplitArray) -  1).' were found in "'.$on.'"');
		}

		if (isset($alias) && $onSplitArray[0] == $alias) {
			return $onSplitArray[2];
		}

		return $onSplitArray[0];
	}

}