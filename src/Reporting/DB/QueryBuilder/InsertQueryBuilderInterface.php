<?php

namespace App\Reporting\DB\QueryBuilder;

use App\Reporting\DB\QueryBuilder\BuilderInterfaceParts\ExpressionFactoryInterface;
use App\Reporting\DB\QueryBuilder\BuilderInterfaceParts\SetsValuesInterface;
use App\Reporting\DB\QueryBuilder\BuilderInterfaceParts\SubQueryBuilderFactoryInterface;

interface InsertQueryBuilderInterface extends QueryBuilderInterface, SetsValuesInterface, SubQueryBuilderFactoryInterface, ExpressionFactoryInterface
{

	/**
	 * @param SelectQueryBuilderInterface $selectQuery
	 * @return InsertQueryBuilderInterface
	 */
	public function insertSubQuery(SelectQueryBuilderInterface $selectQuery);
}