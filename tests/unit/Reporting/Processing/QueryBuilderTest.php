<?php

namespace Tests\Reporting\Processing;

use App\Reporting\DB\QueryBuilderFactoryInterface;
use App\Reporting\Processing\QueryBuilder;
use App\Reporting\Processing\QueryGroup;
use App\Reporting\SelectionsInterface;
use PHPUnit\Framework\TestCase;

class QueryBuilderTest extends TestCase
{
	public function testBuildQuery()
	{
		$qb = $this->getQueryBuilder();

		$selections = $this->createMock(SelectionsInterface::class);
		$queryGroup1 = $this->createMock(QueryGroup::class);

		$qb->buildQuery($selections, [$queryGroup1]);

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

	private function getQueryBuilder()
	{
		$selections = $this->createMock(SelectionsInterface::class);
		$queryGroup1 = $this->createMock(QueryGroup::class);
		$qbFactory = $this->createMock(QueryBuilderFactoryInterface::class);
		$qb = new QueryBuilder($qbFactory);
//		$query = $qb->buildQuery($selections, [$queryGroup1]);

		return $qb;
	}
}