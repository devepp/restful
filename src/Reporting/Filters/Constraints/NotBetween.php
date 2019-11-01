<?php

namespace App\Reporting\Filters\Constraints;

use App\Reporting\DatabaseFields\DatabaseField;
use App\Reporting\DB\QueryBuilder\SelectQueryBuilderInterface;

class NotBetween extends AbstractConstraint
{
	const NAME = 'NotBetween';

	public function filterSql(SelectQueryBuilderInterface $queryBuilder, string $field, $inputs = [])
	{
		$queryBuilder->whereNotBetween($field, $inputs[0], $inputs[1]);
	}

	public function requiredInputs()
	{
		return 1;
	}

	public function directive()
	{
		return 'eb-report-filter-double-text';
	}
}