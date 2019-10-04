<?php

namespace Tests\Reporting\DB\QueryBuilder;

use App\Reporting\DB\QueryBuilder\QueryBuilder;
use PHPUnit\Framework\TestCase;

class QueryBuilderTest extends TestCase
{
	public function testSelect()
	{
		$qb = QueryBuilder::selectBuilder('as_assets');

		$qb->select('equipment_no');

		$query = $qb->getQuery();

		$query->getStatement();

		$this->assertSame($query->getStatement(), 'SELECT equipment_no FROM as_assets');
	}
}