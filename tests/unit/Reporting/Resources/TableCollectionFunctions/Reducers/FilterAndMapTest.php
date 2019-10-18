<?php

namespace Tests\Unit\Reporting\Resources\TableCollectionFunctions\Reducers;

use App\Reporting\Resources\Table;
use App\Reporting\Resources\TableCollection;
use App\Reporting\Resources\TableCollectionFunctions\Reducers\FilterAndMap;
use App\Reporting\Resources\TableCollectionFunctions\TableFilterInterface;
use App\Reporting\Resources\TableCollectionFunctions\TableMapInterface;
use PHPUnit\Framework\TestCase;

class FilterAndMapTest extends TestCase
{
	/**
	 * @dataProvider filtersProvider
	 */
	public function testItFilters($filters, $keeps)
	{
		$myTable = $this->getTable('myTable');

		$tableCollection = TableCollection::fromArray([]);

		$filterAndMap = new FilterAndMap($filters);

		$newCollection = $filterAndMap($tableCollection, $myTable);

		$this->assertEquals($keeps, $newCollection->hasTable($myTable));
	}

	public function testItMaps()
	{
		$myTable = $this->getTable('myTable');

		$tableCollection = TableCollection::fromArray([]);

		$tableMappedTo = $this->getTable('mappedTable');

		$map = $this->createMock(TableMapInterface::class);
		$map->method('__invoke')->willReturn($tableMappedTo);

		$filterAndMap = new FilterAndMap($map);

		$newCollection = $filterAndMap($tableCollection, $myTable);

		$this->assertTrue($newCollection->hasTable($tableMappedTo));
	}

	public function filtersProvider()
	{
		return [
			[[$this->getFilterMock(false)], false],
			[[$this->getFilterMock(true)], true],
			[[$this->getFilterMock(false), $this->getFilterMock(true)], false],
			[[$this->getFilterMock(true), $this->getFilterMock(false)], false],
			[[$this->getFilterMock(true), $this->getFilterMock(true)], true],
		];
	}

	private function getTableCollection($numberOfTables)
	{
		$tables = [];
		for ($i = 0; $i < $numberOfTables; $i++) {
			$tables[] = $this->getTable('Table'.($i + 1));
		}

		return TableCollection::fromArray($tables);
	}

	private function getTable($name)
	{
		return Table::fromString($name, $name.'Alias', []);
	}

	private function getFilterMock($keepsTables)
	{
		$filterMock = $this->createMock(TableFilterInterface::class);

		$filterMock->method('__invoke')->willReturn($keepsTables);


		return $filterMock;
	}

}