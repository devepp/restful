<?php

namespace App\Reporting\DB\QueryBuilder\Traits;

use App\Reporting\DB\QueryBuilder\QueryParts\Join;
use App\Reporting\DB\QueryBuilder\QueryParts\JoinInterface;
use App\Reporting\DB\QueryBuilder\QueryParts\SubQueryJoin;
use App\Reporting\DB\QueryBuilder\QueryParts\TableExpression;
use App\Reporting\DB\QueryBuilder\QueryParts\TableJoin;
use App\Reporting\DB\QueryBuilder\SelectQueryBuilderInterface;

trait Limits
{
	protected $limit;

	protected $offset;

	public function limit($limit, $offset = null)
	{
		$clone = clone $this;

		$clone->limit = $limit;
		$clone->offset = $offset;

		return $clone;
	}

	protected function limitStatementExpression()
	{
		if (!$this->limit) {
			return '';
		}

		$limitOffset = $this->offset ? '?, ?' : '?';

		return ' LIMIT '.$limitOffset;
	}

	protected function getLimitParameters()
	{
		if (!$this->limit) {
			return [];
		}

		if ($this->offset) {
			return [
				$this->offset,
				$this->limit,
			];
		}

		return [
			$this->limit
		];
	}
}