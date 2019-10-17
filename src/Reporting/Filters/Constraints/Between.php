<?php

namespace App\Reporting\Filters\Constraints;

use App\Reporting\DatabaseFields\DatabaseField;
use App\Reporting\DB\QueryBuilder\SelectQueryBuilderInterface;

class Between extends AbstractConstraint
{
	const NAME = 'Between';

	public function filterSql(SelectQueryBuilderInterface $queryBuilder, DatabaseField $dbField, $inputs = [])
	{
		return $queryBuilder->whereBetween($dbField, $inputs[0], $inputs[1]);
	}

	public function requiredInputs()
	{
		return 2;
	}

	public function directive()
	{
		return 'eb-report-filter-double-text';
	}
}