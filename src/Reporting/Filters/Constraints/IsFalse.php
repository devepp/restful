<?php

namespace App\Reporting\Filters\Constraints;

use App\Reporting\DatabaseFields\DatabaseField;
use App\Reporting\DB\QueryBuilder\SelectQueryBuilderInterface;

class IsFalse extends AbstractConstraint
{
	const NAME = 'Is False';

	public function filterSql(SelectQueryBuilderInterface $queryBuilder,  $field, $inputs = [])
	{
		return $queryBuilder->where($field, '=', 0);
	}

	public function requiredInputs()
	{
		return 0;
	}

	public function directive()
	{
		return null;
	}
}