<?php

namespace Tests\Doubles;

use App\Reporting\DB\QueryBuilder\Traits\Limits;

class LimitQueryBuilder
{
	use Limits;

	public function getLimit()
	{
		return $this->limit;
	}

	public function getOffset()
	{
		return $this->offset;
	}

	public function getLimitStatementExpression()
	{
		return $this->limitStatementExpression();
	}

	public function limitParameters()
	{
		return $this->getLimitParameters();
	}
}