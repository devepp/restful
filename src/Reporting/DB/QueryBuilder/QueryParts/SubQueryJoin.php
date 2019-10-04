<?php

namespace App\Reporting\DB\QueryBuilder\QueryParts;

use App\Reporting\DB\QueryBuilder\SelectQueryBuilderInterface;
use InvalidArgumentException;

class SubQueryJoin extends Join
{
	/** @var SubQuery */
	private $subQuery;
	private $on;
	private $type;

	/**
	 * SubqueryJoin constructor.
	 * @param SelectQueryBuilderInterface $subQuery
	 * @param $alias
	 * @param $on
	 * @param $type
	 */
	public function __construct(SelectQueryBuilderInterface $subQuery, $alias, $on, $type)
	{
		if (!in_array(strtoupper($type), Join::TYPES)) {
			throw new InvalidArgumentException('Join Type must be either one of the following : '.implode(',', Join::TYPES).'. "'.$type.'" was set.');
		}

		$this->subQuery = new SubQuery($subQuery, $alias);
		$this->on = $on;
		$this->type = strtoupper($type);
	}

	/**
	 * @return mixed
	 */
	public function getStatementExpression()
	{
		return $this->type.' JOIN '.$this->subQuery->getStatementExpression().' ON '.$this->on;
	}

	/**
	 * @return mixed
	 */
	public function getParameters()
	{
		return $this->subQuery->getParameters();
	}


}