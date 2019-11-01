<?php

namespace Tests\Unit\Reporting\Resources;

use App\Reporting\Processing\QueryGroup;
use App\Reporting\Resources\RelationshipInterface;
use App\Reporting\Resources\Relationships\ManyToOne;
use App\Reporting\Resources\Relationships\OneToOne;
use App\Reporting\Resources\Schema;
use App\Reporting\Resources\Table;
use App\Reporting\Resources\TableCollection;
use App\Reporting\Resources\TableName;
use PHPUnit\Framework\TestCase;

class SchemaTest extends TestCase
{
	public function testGetTable()
	{
		$table = Table::fromString('myTable', 'myTableAlias');
		$tableCollection = TableCollection::fromArray([$table]);

		$schema = new Schema($tableCollection, []);

		$retrievedTable = $schema->getTable('myTableAlias');

		$this->assertSame($table, $retrievedTable);
	}

	public function testHasRelationship()
	{
		$tableName = new TableName('myTable', 'myTableAlias');
		$otherTableName = new TableName('relatedTable', 'relatedTableAlias');
		$otherRelatedTableName = new TableName('otherRelatedTable', 'otherRelatedTableAlias');

		$firstRelationship = new OneToOne($tableName, $otherTableName, '');
		$secondRelationship = new OneToOne($otherTableName, $otherRelatedTableName, '');

		$table = new Table($tableName, [], [$firstRelationship]);
		$relatedTable = new Table($otherTableName, [], [$secondRelationship]);
		$otherRelatedTable = new Table($otherRelatedTableName);

		$tableCollection = TableCollection::fromArray([$table, $relatedTable, $otherRelatedTable]);

		$schema = new Schema($tableCollection, []);

		$hasRelationship = $schema->hasRelationship('myTableAlias', 'otherRelatedTableAlias');

		$this->assertTrue($hasRelationship);
	}

	public function testGetRelationshipPath()
	{
		$tableName = new TableName('myTable', 'myTableAlias');
		$otherTableName = new TableName('relatedTable', 'relatedTableAlias');
		$otherRelatedTableName = new TableName('otherRelatedTable', 'otherRelatedTableAlias');

		$firstRelationship = new OneToOne($tableName, $otherTableName, '');
		$secondRelationship = new OneToOne($otherTableName, $otherRelatedTableName, '');

		$table = new Table($tableName, [], [$firstRelationship]);
		$relatedTable = new Table($otherTableName, [], [$secondRelationship]);
		$otherRelatedTable = new Table($otherRelatedTableName);

		$tableCollection = TableCollection::fromArray([$table, $relatedTable, $otherRelatedTable]);

		$schema = new Schema($tableCollection, []);

		$path = $schema->getRelationshipPath('myTableAlias', 'otherRelatedTableAlias');

		$this->assertEquals(['myTableAlias', 'relatedTableAlias', 'otherRelatedTableAlias'], $path);
	}

	public function testGetQueryGroup()
	{
		$tableName = new TableName('myTable', 'myTableAlias');
		$parentTableName = new TableName('parentTable', 'parentTableAlias');
		$grandParentTableName = new TableName('grandParentTable', 'grandParentTableAlias');
		$childTableName = new TableName('childTable', 'childTableAlias');
		$grandChildTableName = new TableName('grandChildTable', 'grandChildTableAlias');
		$parentOfGrandChildTableName = new TableName('parentOfGrandChildTable', 'parentOfGrandChildTableAlias');

		$toParentRelationship = new ManyToOne($tableName, $parentTableName, '');
		$toGrandParentRelationship = new ManyToOne($parentTableName, $grandParentTableName, '');
		$toMainTableRelationship = new ManyToOne($childTableName, $tableName, '');
		$toChildRelationship = new ManyToOne($grandChildTableName, $childTableName, '');
		$toParentOfGrandchildRelationship = new ManyToOne($grandChildTableName, $parentOfGrandChildTableName, '');

		$table = new Table($tableName, [], [$toParentRelationship]);
		$parentTable = new Table($parentTableName, [], [$toGrandParentRelationship]);
		$grandParentTable = new Table($grandParentTableName, [], []);
		$childTable = new Table($childTableName, [], [$toMainTableRelationship]);
		$grandChildTable = new Table($grandChildTableName, [], [$toChildRelationship, $toParentOfGrandchildRelationship]);
		$parentOfGrandChildTable = new Table($parentOfGrandChildTableName, [], []);

		$tableCollection = TableCollection::fromArray([$table, $parentTable, $grandParentTable, $childTable, $grandChildTable, $parentOfGrandChildTable]);

		$schema = new Schema($tableCollection, []);

		$testQueryGroup = $schema->getQueryGroup($table, $tableCollection);

		$expectedQueryGroup = new QueryGroup(
			$table,
			TableCollection::fromArray([$table, $parentTable, $grandParentTable]),
			TableCollection::fromArray([$table]),
			[
				new QueryGroup($childTable, TableCollection::fromArray([$childTable]), TableCollection::fromArray([$table, $childTable])),
				new QueryGroup($grandChildTable, TableCollection::fromArray([$grandChildTable, $parentOfGrandChildTable]), TableCollection::fromArray([$table, $childTable, $grandChildTable])),
			]
		);

		$this->assertEquals($expectedQueryGroup, $testQueryGroup);
	}
}