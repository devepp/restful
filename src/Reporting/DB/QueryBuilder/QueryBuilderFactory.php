<?php

namespace App\Reporting\DB\QueryBuilder;

use App\Reporting\DB\QueryBuilder\Builders\Delete;
use App\Reporting\DB\QueryBuilder\Builders\Insert;
use App\Reporting\DB\QueryBuilder\Builders\Select;
use App\Reporting\DB\QueryBuilder\Builders\Update;
use App\Reporting\DB\QueryBuilderFactoryInterface;

class QueryBuilderFactory implements QueryBuilderFactoryInterface
{
	public function selectFrom($tableExpression)
	{
		return new Select($tableExpression);
	}

	public function update($tableExpression)
	{
		return new Update($tableExpression);
	}

	public function insertInto($tableExpression)
	{
		return new Insert($tableExpression);
	}

	public function deleteFrom($tableExpression)
	{
		return new Delete($tableExpression);
	}
}