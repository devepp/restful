<?php

namespace App\Reporting\Filters\Constraints;

use App\Reporting\DatabaseFields\DatabaseField;
use App\Reporting\DB\QueryBuilder\SelectQueryBuilderInterface;

class IsTrue extends AbstractConstraint
{
	const NAME = 'Is True';

	public function filterSql(SelectQueryBuilderInterface $queryBuilder, DatabaseField $dbField, $inputs = [])
	{
		return $queryBuilder->where($dbField, '=', 1);
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