<?php

namespace App\Reporting\Filters\Constraints;

use App\Reporting\DatabaseFields\DatabaseField;
use App\Reporting\DB\QueryBuilder\SelectQueryBuilderInterface;

class GreaterThan extends AbstractConstraint
{
	const NAME = 'GreaterThan';

	public function filterSql(SelectQueryBuilderInterface $queryBuilder, string $field, $inputs = [])
	{
		return $queryBuilder->where($field, '>', $inputs[0]);
	}

	public function requiredInputs()
	{
		return 1;
	}

	public function directive()
	{
		return 'eb-report-filter-single-text';
	}
}