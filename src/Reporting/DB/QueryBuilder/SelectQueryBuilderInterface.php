<?php

namespace App\Reporting\DB\QueryBuilder;

use App\Reporting\DB\QueryBuilder\BuilderInterfaceParts\ExpressionFactoryInterface;
use App\Reporting\DB\QueryBuilder\BuilderInterfaceParts\JoinsInterface;
use App\Reporting\DB\QueryBuilder\BuilderInterfaceParts\OrdersInterface;
use App\Reporting\DB\QueryBuilder\BuilderInterfaceParts\SubQueryBuilderFactoryInterface;
use App\Reporting\DB\QueryBuilder\BuilderInterfaceParts\WhereBuilderInterface;

interface SelectQueryBuilderInterface extends QueryBuilderInterface, WhereBuilderInterface, JoinsInterface, OrdersInterface, SubQueryBuilderFactoryInterface, ExpressionFactoryInterface
{
	/**
	 * @param mixed ...$fieldExpressions
	 * @return SelectQueryBuilderInterface - cloned query builder
	 */
	public function select(...$fieldExpressions);

	/**
	 * @param SelectQueryBuilderInterface $queryBuilder
	 * @param $alias
	 * @return SelectQueryBuilderInterface - cloned query builder
	 */
	public function selectSubQuery(SelectQueryBuilderInterface $queryBuilder, $alias);

	/**
	 * @param $field
	 * @return SelectQueryBuilderInterface - cloned query builder
	 */
	public function groupBy($field);

	/**
	 * @param $limit
	 * @param null $offset
	 * @return SelectQueryBuilderInterface - cloned query builder
	 */
	public function limit($limit, $offset = null);
}