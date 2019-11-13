<?php

namespace App\Reporting\Filters\Constraints;

use App\Reporting\DatabaseFields\DatabaseField;
use App\Reporting\DB\QueryBuilder\SelectQueryBuilderInterface;

class OneOf extends AbstractConstraint
{
	const NAME = 'OneOf';

	public function filterSql(SelectQueryBuilderInterface $queryBuilder, $field, $inputs = [])
	{
		return $queryBuilder->whereIn($field, $inputs[0]);
	}

	public function requiredInputs()
	{
		return 1;
	}

	public function directive()
	{
		return 'eb-report-filter-multi-select';
	}
}