<?php

namespace App\Reporting\Filters\Constraints;

use App\Reporting\DatabaseFields\DatabaseField;
use App\Reporting\DB\QueryBuilder\SelectQueryBuilderInterface;

class EndsWith extends AbstractConstraint
{
	const NAME = 'EndsWith';

	public function filterSql(SelectQueryBuilderInterface $queryBuilder,  $field, $inputs = [])
	{
		return $queryBuilder->where($field, 'LIKE', '%'.$inputs[0]);
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