<?php

namespace Tests\Unit\Reporting\Resources\TableCollectionFunctions\Filters;

use App\Reporting\Resources\RelationshipInterface;
use App\Reporting\Resources\Schema;
use App\Reporting\Resources\Table;
use App\Reporting\Resources\TableCollectionFunctions\Filters\SameNodeAs;
use PHPUnit\Framework\MockObject\Stub\ReturnStub;
use PHPUnit\Framework\TestCase;
use Tests\Doubles\SameNodeSchema;

class SameNodeAsTest extends TestCase
{
	public function testSameNode()
	{
		$compareTable = $this->createMock(Table::class);
		$compareTable->method('relatedTo')->willReturn(true);
		$compareTable->method('hasOne')->willReturn(true);

		$tableOnSameNode = $this->createMock(Table::class);

		$schema = new SameNodeSchema($compareTable, $tableOnSameNode);

		$sameNode = new SameNodeAs($compareTable, $schema);

		$this->assertTrue($sameNode($tableOnSameNode));
	}

	public function testNotSameNode()
	{
		$compareTable = $this->createMock(Table::class);
		$compareTable->method('relatedTo')->willReturn(true);
		$compareTable->method('hasOne')->willReturn(false);

		$tableNotOnSameNode = $this->createMock(Table::class);

		$schema = new SameNodeSchema($compareTable, $tableNotOnSameNode);

		$sameNode = new SameNodeAs($compareTable, $schema);

		$this->assertFalse($sameNode($tableNotOnSameNode));
	}
}