<?php

namespace App\Reporting\DB\QueryBuilder\Traits;

use App\Reporting\DB\QueryBuilder\Builders\Select;
use App\Reporting\DB\QueryBuilder\SelectQueryBuilderInterface;

trait MakesSubQueryBuilder
{
	/**
	 * @param $tableExpression
	 * @return SelectQueryBuilderInterface
	 */
	public function subQuery($tableExpression)
	{
		return new Select($tableExpression);
	}
}