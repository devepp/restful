<?php

namespace App\Reporting\DB\QueryBuilder;

use App\Reporting\DB\QueryBuilder\BuilderInterfaceParts\ExpressionFactoryInterface;
use App\Reporting\DB\QueryBuilder\BuilderInterfaceParts\JoinsInterface;
use App\Reporting\DB\QueryBuilder\BuilderInterfaceParts\OrdersInterface;
use App\Reporting\DB\QueryBuilder\BuilderInterfaceParts\SubQueryBuilderFactoryInterface;
use App\Reporting\DB\QueryBuilder\BuilderInterfaceParts\WhereBuilderInterface;

interface DeleteQueryBuilderInterface extends QueryBuilderInterface, WhereBuilderInterface, JoinsInterface, OrdersInterface, SubQueryBuilderFactoryInterface, ExpressionFactoryInterface
{

	/**
	 * @param $limit
	 * @return DeleteQueryBuilderInterface
	 */
	public function limit($limit);
}