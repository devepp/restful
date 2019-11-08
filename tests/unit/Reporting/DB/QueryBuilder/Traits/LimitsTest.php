<?php

namespace Tests\Unit\Reporting\DB\QueryBuilder\Traits;

use PHPUnit\Framework\TestCase;
use Tests\Doubles\LimitQueryBuilder;

class LimitsTest extends TestCase
{
	/**
	 * @dataProvider limitProvider
	 *
	 * @param $limit
	 * @param $offset
	 * @param $expectedExpression
	 * @param $expectedParameters
	 */
	public function testLimit($limit, $offset, $expectedExpression, $expectedParameters)
	{
		$qb = new LimitQueryBuilder();

		$qb = $qb->limit($limit, $offset);

		$this->assertEquals($limit, $qb->getLimit(), 'Limit is not the same');
		$this->assertEquals($offset, $qb->getOffset(), 'Offset is not the same');
		$this->assertEquals($expectedExpression, $qb->getLimitStatementExpression(), 'Expression is not correct');
		$this->assertEquals($expectedParameters, $qb->limitParameters(), 'Parameters are not correct');
	}

	public function limitProvider()
	{
		return [
			[20, 10, ' LIMIT ?, ?', [10, 20]],
			[20, 0, ' LIMIT ?', [20]],
			[20, null, ' LIMIT ?', [20]],
			[null, null, '', []],
		];
	}
}