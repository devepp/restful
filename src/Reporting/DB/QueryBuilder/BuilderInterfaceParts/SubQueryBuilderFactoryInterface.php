<?php

namespace App\Reporting\DB\QueryBuilder\BuilderInterfaceParts;

use App\Reporting\DB\QueryBuilder\SelectQueryBuilderInterface;

interface SubQueryBuilderFactoryInterface
{
	/**
	 * @param $tableExpression
	 * @return SelectQueryBuilderInterface - new builder
	 */
	public function subQuery($tableExpression);
}