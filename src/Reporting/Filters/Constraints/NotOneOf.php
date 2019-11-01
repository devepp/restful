<?php

namespace App\Reporting\Filters\Constraints;

use App\Reporting\DatabaseFields\DatabaseField;
use App\Reporting\DB\QueryBuilder\SelectQueryBuilderInterface;

class NotOneOf extends AbstractConstraint
{
	const NAME = 'NotOneOf';

	public function filterSql(SelectQueryBuilderInterface $queryBuilder, string $field, $inputs = [])
	{
		return $queryBuilder->whereNotIn($field, $inputs[0]);
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