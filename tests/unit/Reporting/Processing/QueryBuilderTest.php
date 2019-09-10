<?php

namespace Tests\Reporting\Processing;

use App\Reporting\Form;
use App\Reporting\Processing\QueryBuilder;
use App\Reporting\Processing\QueryGroup;
use App\Reporting\SelectionsInterface;
use PHPUnit\Framework\TestCase;

class QueryBuilderTest extends TestCase
{
	public function testBuildQuery()
	{
		$selections = $this->createMock(SelectionsInterface::class);
		$queryGroup1 = $this->createMock(QueryGroup::class);
		$qb = new QueryBuilder($selections, [$queryGroup1]);

//		$query = $qb->buildQuery();

		$this->assertSame($qb, $qb);
	}

	public function testBuildSqlPrimaryGroup()
	{
		$this->assertTrue(true);
	}


	public function testBuildSqlSubQuery()
	{
		$this->assertTrue(true);
	}
}