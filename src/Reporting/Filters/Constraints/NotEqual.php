<?php

namespace App\Reporting\Filters\Constraints;

use App\Reporting\DatabaseFields\DatabaseField;
use App\Reporting\DB\QueryBuilder\SelectQueryBuilderInterface;

class NotEqual extends AbstractConstraint
{
	const NAME = 'NotEqual';

	public function filterSql(SelectQueryBuilderInterface $queryBuilder,  $field, $inputs = [])
	{
		return $queryBuilder->where($field, '!=', $inputs[0]);
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