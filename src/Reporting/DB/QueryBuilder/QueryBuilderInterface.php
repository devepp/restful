<?php

namespace App\Reporting\DB\QueryBuilder;

use App\Reporting\DB\Query;

interface QueryBuilderInterface extends SqlExpressionInterface
{
	/**
	 * @return Query
	 */
	public function getQuery();
}