<?php

namespace Tests\Unit\Reporting\Resources\TableCollectionFunctions\Filters;

use App\Reporting\Resources\Schema;
use App\Reporting\Resources\Table;
use App\Reporting\Resources\TableCollectionFunctions\Filters\DirectlyRelatedTo;
use PHPUnit\Framework\TestCase;

class DirectlyRelatedTablesTest extends TestCase
{
	public function testReturnsTrueIfDirectlyRelated()
	{
		$compsreTable = $this->createMock(Table::class);
		$compsreTable->method('relatedTo')->willReturn(true);

		$relatedTable = $this->createMock(Table::class);

		$directlyRelated = new DirectlyRelatedTo($compsreTable);

		$this->assertTrue($directlyRelated($relatedTable));
	}

	public function testReturnsTrueIfDirectlyRelatedByOtherTable()
	{
		$compsreTable = $this->createMock(Table::class);

		$relatedTable = $this->createMock(Table::class);
		$relatedTable->method('relatedTo')->willReturn(true);

		$directlyRelated = new DirectlyRelatedTo($compsreTable);

		$this->assertTrue($directlyRelated($relatedTable));
	}
}