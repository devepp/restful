<?php

namespace Tests\Reporting\Resources\TableCollectionFunctions\Filters;

use App\Reporting\Resources\RelationshipInterface;
use App\Reporting\Resources\Schema;
use App\Reporting\Resources\Table;
use App\Reporting\Resources\TableCollectionFunctions\Filters\SameNodeAs;
use PHPUnit\Framework\TestCase;

class SameNodeAsTest extends TestCase
{
	public function testSameNode()
	{
		$compareTable = $this->createMock(Table::class);
		$tableOnSameNode = $this->createMock(Table::class);

		$relationship = $this->createMock(RelationshipInterface::class);
		$relationship->method('tableHasOne')->willReturn(true);

		$schema = $this->createMock(Schema::class);
		$schema->method('getRelationshipPath')->willReturn(['','']);
		$schema->method('getRelationship')->willReturn($relationship);

		$sameNode = new SameNodeAs($compareTable, $schema);

		$this->assertTrue($sameNode($tableOnSameNode));
	}

	public function testNotSameNode()
	{
		$compareTable = $this->createMock(Table::class);
		$tableNotOnSameNode = $this->createMock(Table::class);

		$relationship = $this->createMock(RelationshipInterface::class);
		$relationship->method('tableHasOne')->willReturn(false);

		$schema = $this->createMock(Schema::class);
		$schema->method('getRelationshipPath')->willReturn(['','']);
		$schema->method('getRelationship')->willReturn($relationship);

		$sameNode = new SameNodeAs($compareTable, $schema);

		$this->assertFalse($sameNode($tableNotOnSameNode));
	}
}