<?php

namespace Tests\Unit\Reporting\DB\QueryBuilder;

use App\Reporting\DB\Query;
use App\Reporting\DB\QueryBuilder\QueryParts\Expression;
use App\Reporting\DB\QueryBuilder\Select;
use App\Reporting\DB\QueryBuilder\SelectQueryBuilderInterface;
use PHPUnit\Framework\TestCase;

class SelectTest extends TestCase
{
	public function testSimpleSelect()
	{
		$qb = new Select('as_assets');
		$qb = $qb->select('equipment_no');

		$expectedQuery = new Query('SELECT equipment_no FROM as_assets', []);
		$this->assertEquals($qb->getQuery(), $expectedQuery);
	}

	public function testMultipleSelect()
	{
		$qb = new Select('as_assets');
		$qb = $qb->select('id')
			->select('equipment_no');

		$expectedQuery = new Query('SELECT id, equipment_no FROM as_assets', []);
		$this->assertEquals($qb->getQuery(), $expectedQuery);
	}

	public function testSelectSubQuerySelect()
	{
		$qb = new Select('as_assets');
		$qb = $qb->selectSubQuery(
			$qb->subQuery('as_assets')
				->select('MAX(as_assets.retired_on) most_recent'),
			'most_recent'
		);

		$expectedQuery = new Query('SELECT (SELECT MAX(as_assets.retired_on) most_recent FROM as_assets) most_recent FROM as_assets', []);
		$this->assertEquals($qb->getQuery(), $expectedQuery);
	}

	public function testWhereStatement()
	{
		$qb = new Select('as_assets');
		$qb = $qb->where('as_assets.id', '<=', 25);

		$expectedQuery = new Query('SELECT * FROM as_assets WHERE as_assets.id <= ?', [25]);
		$this->assertEquals($qb->getQuery(), $expectedQuery);
	}

	public function testMultipleWhere()
	{
		$qb = new Select('as_assets');
		$qb = $qb->where('as_assets.id', '<=', 25)
			->where('as_assets.retired_on', '>', '2012-01-01');

		$expectedQuery = new Query('SELECT * FROM as_assets WHERE as_assets.id <= ? AND as_assets.retired_on > ?', [25, '2012-01-01']);
		$this->assertEquals($qb->getQuery(), $expectedQuery);
	}

	public function testOrWhere()
	{
		$qb = new Select('as_assets');
		$qb = $qb->where('as_assets.id', '<=', 25)
			->orWhere('as_assets.retired_on', '>', '2012-01-01');

		$expectedQuery = new Query('SELECT * FROM as_assets WHERE as_assets.id <= ? OR as_assets.retired_on > ?', [25, '2012-01-01']);
		$this->assertEquals($qb->getQuery(), $expectedQuery);
	}

	public function testWhereExists()
	{
		$qb = new Select('wo_orders');
		$qb = $qb->whereExists(
			$qb->subQuery('wo_jobs')
				->select('1')
				->whereRaw('wo_jobs.work_order_id = wo_orders.id')
				->where('wo_jobs.created_on', '<', '2018-01-01')
		);

		$expectedQuery = new Query('SELECT * FROM wo_orders WHERE EXISTS(SELECT 1 FROM wo_jobs WHERE wo_jobs.work_order_id = wo_orders.id AND wo_jobs.created_on < ?)', ['2018-01-01']);
		$this->assertEquals($qb->getQuery(), $expectedQuery);
	}

	public function testWhereNotExists()
	{
		$qb = new Select('wo_orders');
		$qb = $qb->whereNotExists(
			$qb->subQuery('wo_jobs')
				->select('1')
				->whereRaw('wo_jobs.work_order_id = wo_orders.id')
				->where('wo_jobs.created_on', '<', '2018-01-01')
		);

		$expectedQuery = new Query('SELECT * FROM wo_orders WHERE NOT EXISTS(SELECT 1 FROM wo_jobs WHERE wo_jobs.work_order_id = wo_orders.id AND wo_jobs.created_on < ?)', ['2018-01-01']);
		$this->assertEquals($qb->getQuery(), $expectedQuery);
	}

	public function testWhereBetween()
	{
		$qb = new Select('as_assets');
		$qb = $qb->whereBetween('as_assets.retired_on', '2018-01-01', '2018-01-31');

		$expectedQuery = new Query('SELECT * FROM as_assets WHERE as_assets.retired_on BETWEEN ? AND ?', ['2018-01-01', '2018-01-31']);
		$this->assertEquals($qb->getQuery(), $expectedQuery);
	}

	public function testWhereNotBetween()
	{
		$qb = new Select('as_assets');
		$qb = $qb->whereNotBetween('as_assets.retired_on', '2018-01-01', '2018-01-31');

		$expectedQuery = new Query('SELECT * FROM as_assets WHERE as_assets.retired_on NOT BETWEEN ? AND ?', ['2018-01-01', '2018-01-31']);
		$this->assertEquals($qb->getQuery(), $expectedQuery);
	}

	public function testWhereNull()
	{
		$qb = new Select('as_assets');
		$qb = $qb->whereNull('as_assets.retired_on');

		$expectedQuery = new Query('SELECT * FROM as_assets WHERE as_assets.retired_on IS NULL', []);
		$this->assertEquals($qb->getQuery(), $expectedQuery);
	}

	public function testWhereNotNull()
	{
		$qb = new Select('as_assets');
		$qb = $qb->whereNotNull('as_assets.retired_on');

		$expectedQuery = new Query('SELECT * FROM as_assets WHERE as_assets.retired_on IS NOT NULL', []);
		$this->assertEquals($qb->getQuery(), $expectedQuery);
	}

	public function testWhereIn()
	{
		$qb = new Select('as_assets');
		$qb = $qb->whereIn('as_assets.id', [15,16,17]);

		$expectedQuery = new Query('SELECT * FROM as_assets WHERE as_assets.id IN (?,?,?)', [15,16,17]);
		$this->assertEquals($qb->getQuery(), $expectedQuery);
	}

	public function testWhereNotIn()
	{
		$qb = new Select('as_assets');
		$qb = $qb->whereNotIn('as_assets.id', [15,16,17]);

		$expectedQuery = new Query('SELECT * FROM as_assets WHERE as_assets.id NOT IN (?,?,?)', [15,16,17]);
		$this->assertEquals($qb->getQuery(), $expectedQuery);
	}

	public function testWhereRaw()
	{
		$qb = new Select('as_assets');
		$qb = $qb->whereRaw('MONTH(as_assets.retired_on) < MONTH(as_assets.created_on)');

		$expectedQuery = new Query('SELECT * FROM as_assets WHERE MONTH(as_assets.retired_on) < MONTH(as_assets.created_on)', []);
		$this->assertEquals($qb->getQuery(), $expectedQuery);
	}

	public function testJoin()
	{
		$qb = new Select('wo_orders');
		$qb = $qb->join('wo_jobs jobs', 'jobs.work_order_id = wo_orders.id');

		$expectedQuery = new Query('SELECT * FROM wo_orders INNER JOIN wo_jobs jobs ON jobs.work_order_id = wo_orders.id', []);
		$this->assertEquals($qb->getQuery(), $expectedQuery);
	}

	public function testJoinSubQuery()
	{
		$qb = new Select('wo_jobs jobs');
		$qb = $qb->joinSubQuery(
			$qb->subQuery('wo_accounting_notes')
				->select('wo_accounting_notes.job_id, SUM(wo_accounting_notes.price) as total_price')
				->where('wo_accounting_notes.id', '>', 5),
			'notes',
			'notes.job_id = jobs.id',
			'left'
		);

		$expectedQuery = new Query('SELECT * FROM wo_jobs jobs LEFT JOIN (SELECT wo_accounting_notes.job_id, SUM(wo_accounting_notes.price) as total_price FROM wo_accounting_notes WHERE wo_accounting_notes.id > ?) notes ON notes.job_id = jobs.id', [5]);
		$this->assertEquals($qb->getQuery(), $expectedQuery);
	}

	public function testGroupBy()
	{
		$qb = new Select('wo_orders');
		$qb = $qb->groupBy('wo_orders.facility_id');

		$expectedQuery = new Query('SELECT * FROM wo_orders GROUP BY wo_orders.facility_id', []);
		$this->assertEquals($qb->getQuery(), $expectedQuery);
	}

	public function testGroupBys()
	{
		$qb = new Select('wo_orders');
		$qb = $qb->groupBy('wo_orders.facility_id');
		$qb = $qb->groupBy('wo_orders.reason_id');

		$expectedQuery = new Query('SELECT * FROM wo_orders GROUP BY wo_orders.facility_id, wo_orders.reason_id', []);
		$this->assertEquals($qb->getQuery(), $expectedQuery);
	}

	public function testHaving()
	{
		$qb = new Select('wo_orders');
		$qb = $qb->groupBy('wo_orders.facility_id');
		$qb = $qb->having('COUNT(wo_orders.facility_id) > 2');

		$expectedQuery = new Query('SELECT * FROM wo_orders GROUP BY wo_orders.facility_id HAVING COUNT(wo_orders.facility_id) > 2', []);
		$this->assertEquals($qb->getQuery(), $expectedQuery);
	}

	public function testHavings()
	{
		$qb = new Select('wo_orders');
		$qb = $qb->groupBy('wo_orders.facility_id');
		$qb = $qb->having('COUNT(wo_orders.facility_id) > 2');
		$qb = $qb->having('MAX(wo_orders.issued) > "2018-01-01"');

		$expectedQuery = new Query('SELECT * FROM wo_orders GROUP BY wo_orders.facility_id HAVING COUNT(wo_orders.facility_id) > 2, MAX(wo_orders.issued) > "2018-01-01"', []);
		$this->assertEquals($qb->getQuery(), $expectedQuery);
	}

	public function testHavingWithExpression()
	{
		$qb = new Select('wo_orders');
		$qb = $qb->groupBy('wo_orders.facility_id');
		$qb = $qb->having(new Expression('COUNT(wo_orders.facility_id) > ?', [2]));

		$expectedQuery = new Query('SELECT * FROM wo_orders GROUP BY wo_orders.facility_id HAVING COUNT(wo_orders.facility_id) > ?', [2]);
		$this->assertEquals($qb->getQuery(), $expectedQuery);
	}

	public function testLimit()
	{
		$qb = new Select('wo_orders');
		$qb = $qb->limit(5);

		$expectedQuery = new Query('SELECT * FROM wo_orders LIMIT ?', [5]);
		$this->assertEquals($qb->getQuery(), $expectedQuery);
	}

	public function testOffset()
	{
		$qb = new Select('wo_orders');
		$qb = $qb->limit(5, 10);

		$expectedQuery = new Query('SELECT * FROM wo_orders LIMIT ?, ?', [10,5]);
		$this->assertEquals($qb->getQuery(), $expectedQuery);
	}

	public function subQuery()
	{
		$qb = new Select('wo_orders');
		$qb = $qb->limit(5, 10);
		$subQuery = $qb->subQuery('wo_jobs');

		$this->assertInstanceOf(SelectQueryBuilderInterface::class, $subQuery);
	}

	public function testComplexSelect()
	{
		$qb = new Select('wo_orders work_order');
		$qb = $qb->select('work_order.id', 'jobs.state', 'notes.total_price')
			->selectSubQuery(
				$qb->subQuery('wo_jobs recent_jobs')
					->select('MAX(recent_jobs.created_on) most_recent')
					->whereRaw('recent_jobs.id = jobs.id'),
				'most_recent_created_on_date'
			)
			->join('wo_jobs jobs', 'work_order.id = jobs.work_order_id', 'left')
			->joinSubQuery(
				$qb->subQuery('wo_accounting_notes')
					->select('wo_accounting_notes.job_id, SUM(wo_accounting_notes.price) as total_price'),
				'notes',
				'notes.job_id = jobs.id',
				'left'
			)
			->whereRaw('notes.job_id IS NOT NULL')
			->where('work_order.id', '>', 5)
			->limit(2);

		$expectedQuery = new Query('SELECT work_order.id, jobs.state, notes.total_price, (SELECT MAX(recent_jobs.created_on) most_recent FROM wo_jobs recent_jobs WHERE recent_jobs.id = jobs.id) most_recent_created_on_date FROM wo_orders work_order LEFT JOIN wo_jobs jobs ON work_order.id = jobs.work_order_id LEFT JOIN (SELECT wo_accounting_notes.job_id, SUM(wo_accounting_notes.price) as total_price FROM wo_accounting_notes) notes ON notes.job_id = jobs.id WHERE notes.job_id IS NOT NULL AND work_order.id > ? LIMIT ?', [5, 2]);
		$this->assertEquals($qb->getQuery(), $expectedQuery);
	}
}
