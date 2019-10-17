<?php

namespace Tests\Reporting\Resources\TableCollectionFunctions\Filters;

use App\Reporting\Resources\Table;
use App\Reporting\Resources\TableCollection;
use App\Reporting\Resources\TableCollectionFunctions\Filters\Exclude;
use PHPUnit\Framework\TestCase;

class ExcludeTablesTest extends TestCase
{
	public function testItExcludesSpecifiedTables()
	{
		$compareTable = $this->getTableMock('compareTable');

		$excludeTable1 = $this->getTableMock('excludeTable1');
		$excludeTable2 = $this->getTableMock('excludeTable2');

		$tablesToExclude = TableCollection::fromArray([$excludeTable1, $excludeTable2]);
		$excludeFilter = new Exclude($tablesToExclude);

		$included = $excludeFilter($compareTable);

		$this->assertTrue($included);
	}

	private function getTableMock($name)
	{
		$tableMock = $this->createMock(Table::class);
		$tableMock->method('name')->willReturn($name);
		$tableMock->method('alias')->willReturn($name);

		return $tableMock;
	}
}