<?php

namespace App\Reporting\Filters\Constraints;

use App\Reporting\DatabaseFields\DatabaseField;
use App\Reporting\DB\QueryBuilder\SelectQueryBuilderInterface;

class Contains extends AbstractConstraint
{
	const NAME = 'Contains';

	public function filterSql(SelectQueryBuilderInterface $queryBuilder, DatabaseField $dbField, $inputs = [])
	{
		return $queryBuilder->where($dbField, 'LIKE', '%'.$inputs[0].'%');
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