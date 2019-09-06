<?php

use App\Reporting\DB\QueryBuilder\QueryParts\TableExpression;
use App\Reporting\DB\QueryBuilder\QueryTypes\Type;
use PHPUnit\Framework\TestCase;

class SelectTest extends TestCase
{
	/**
	 * @dataProvider fieldLists
	 */
	public function testSelect($tableName, $fields, $joins, $wheres, $groupBys, $havings, $orderBys, $expectedResult)
	{
		$select = Type::select();
		$table = new TableExpression($tableName);

		$query = $select->compileStatement($table, $fields, $joins, $wheres, $groupBys, $havings, $orderBys);

		$this->assertSame($query, $expectedResult);
	}

	public function testTypeMethod()
	{
		$select = Type::select();

		$this->assertEquals($select->type(), Type::SELECT);
	}

	public function fieldLists()
	{
		return [
			[
				'users u',
				[],
				[],
				[],
				[],
				[],
				[],
				'SELECT * FROM users u'
			],
			[
				'users',
				[],
				[],
				[],
				[],
				[],
				[],
				'SELECT * FROM users'
			],
			[
				'users',
				['id', 'email'],
				[],
				[],
				[],
				[],
				[],
				'SELECT id, email FROM users'
			],
			[
				'users',
				['id', 'users.email'],
				[],
				[],
				[],
				[],
				[],
				'SELECT id, users.email FROM users'
			],
			[
				'users',
				[],
				['JOIN user_facilities ON user_facilities.user_id = users.id'],
				[],
				[],
				[],
				[],
				'SELECT * FROM users JOIN user_facilities ON user_facilities.user_id = users.id'
			],
			[
				'users',
				[],
				[
					'JOIN user_facilities ON user_facilities.user_id = users.id',
					'LEFT JOIN facilities ON facilities.id = user_facilities.ref_id AND type = "UserFacility"'
				],
				[],
				[],
				[],
				[],
				'SELECT * FROM users JOIN user_facilities ON user_facilities.user_id = users.id LEFT JOIN facilities ON facilities.id = user_facilities.ref_id AND type = "UserFacility"'
			],
			[
				'users',
				[],
				[],
				['id = 1'],
				[],
				[],
				[],
				'SELECT * FROM users WHERE id = 1'
			],
			[
				'users',
				[],
				[],
				['id > 1', 'AND email LIKE "%ebasefm.com"'],
				[],
				[],
				[],
				'SELECT * FROM users WHERE id > 1 AND email LIKE "%ebasefm.com"'
			],
			[
				'users',
				[],
				[],
				[],
				['users.last_name'],
				[],
				[],
				'SELECT * FROM users GROUP BY users.last_name'
			],
			[
				'users',
				[],
				[],
				[],
				['last_name', 'first_name'],
				[],
				[],
				'SELECT * FROM users GROUP BY last_name, first_name'
			],
			[
				'users',
				[],
				[],
				[],
				['users.last_name'],
				['COUNT(users.id) > 1'],
				[],
				'SELECT * FROM users GROUP BY users.last_name HAVING COUNT(users.id) > 1'
			],
			[
				'users',
				[],
				[],
				[],
				['users.last_name'],
				['COUNT(users.id) > 1', 'AND MAX(users.id) < 54'],
				[],
				'SELECT * FROM users GROUP BY users.last_name HAVING COUNT(users.id) > 1 AND MAX(users.id) < 54'
			],
			[
				'users',
				[],
				[],
				[],
				[],
				[],
				['users.last_name', 'users.first_name DESC'],
				'SELECT * FROM users ORDER BY users.last_name, users.first_name DESC'
			],
		];
	}
}