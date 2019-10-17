<?php

namespace Tests\Reporting\Resources\TableCollectionFunctions\Filters;

use App\Reporting\Resources\Schema;
use App\Reporting\Resources\Table;
use App\Reporting\Resources\TableCollectionFunctions\Filters\DirectlyRelatedTo;
use PHPUnit\Framework\TestCase;

class DirectlyRelatedTablesTest extends TestCase
{
	public function testReturnsTrueIfDirectlyRelated()
	{
		$compsreTable = $this->createMock(Table::class);
		$relatedTable = $this->createMock(Table::class);

		$schema = $this->createMock(Schema::class);
		$schema->method('hasDirectRelationship')->willReturn(true);

		$directlyRelated = new DirectlyRelatedTo($compsreTable, $schema);

		$this->assertTrue($directlyRelated($relatedTable));
	}
}