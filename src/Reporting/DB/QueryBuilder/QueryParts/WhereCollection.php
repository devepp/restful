<?php

namespace App\Reporting\DB\QueryBuilder\QueryParts;


class WhereCollection
{
	private $wheres = [];

	/**
	 * WhereCollection constructor.
	 * @param array $wheres
	 */
	public function __construct($wheres)
	{
		foreach ($wheres as $where)
		$this->wheres = $wheres;
	}

	public function withWhere(Where $whereClause)
	{
		$collection = clone $this;
		$collection->wheres[] = $whereClause;
		return $collection;
	}

	private function getWheres()
	{
		return $this->wheres;
	}

}