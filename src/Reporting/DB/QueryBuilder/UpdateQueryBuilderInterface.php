<?php

namespace App\Reporting\DB\QueryBuilder;

use App\Reporting\DB\QueryBuilder\BuilderInterfaceParts\ExpressionFactoryInterface;
use App\Reporting\DB\QueryBuilder\BuilderInterfaceParts\JoinsInterface;
use App\Reporting\DB\QueryBuilder\BuilderInterfaceParts\OrdersInterface;
use App\Reporting\DB\QueryBuilder\BuilderInterfaceParts\SetsValuesInterface;
use App\Reporting\DB\QueryBuilder\BuilderInterfaceParts\SubQueryBuilderFactoryInterface;
use App\Reporting\DB\QueryBuilder\BuilderInterfaceParts\WhereBuilderInterface;

interface UpdateQueryBuilderInterface extends QueryBuilderInterface, WhereBuilderInterface, JoinsInterface, SetsValuesInterface, OrdersInterface, SubQueryBuilderFactoryInterface, ExpressionFactoryInterface
{

	/**
	 * @param $limit
	 * @return UpdateQueryBuilderInterface
	 */
	public function limit($limit);
}