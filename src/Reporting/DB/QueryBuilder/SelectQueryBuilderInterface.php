<?php

namespace App\Reporting\DB\QueryBuilder;

use App\Reporting\DB\QueryBuilder\QueryParts\Expression;
use App\Reporting\DB\QueryBuilder\QueryParts\WhereCollection;

interface SelectQueryBuilderInterface extends QueryBuilderInterface, WhereBuilderInterface, JoinsInterface
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
	 * @param $field
	 * @param string $direction
	 * @return SelectQueryBuilderInterface - cloned query builder
	 */
	public function orderBy($field, $direction = 'ASC');

	/**
	 * @param $limit
	 * @param null $offset
	 * @return SelectQueryBuilderInterface - cloned query builder
	 */
	public function limit($limit, $offset = null);

	/**
	 * @param $tableExpression
	 * @return SelectQueryBuilderInterface - new query builder
	 */
	public function subQuery($tableExpression);

	/**
	 * @param $expressionString
	 * @param array $parameters
	 * @return Expression
	 */
	public function expression($expressionString, $parameters = []);
}