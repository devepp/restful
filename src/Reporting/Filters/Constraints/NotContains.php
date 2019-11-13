<?php

namespace App\Reporting\Filters\Constraints;

use App\Reporting\DatabaseFields\DatabaseField;
use App\Reporting\DB\QueryBuilder\SelectQueryBuilderInterface;

class NotContains extends AbstractConstraint
{
	const NAME = 'NotContains';

	public function filterSql(SelectQueryBuilderInterface $queryBuilder,  $field, $inputs = [])
	{
		return $queryBuilder->where($field, 'NOT LIKE', '%'.$inputs[0].'$');
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