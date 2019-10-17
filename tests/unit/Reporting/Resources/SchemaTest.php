<?php

use App\Reporting\Processing\QueryGroup;
use App\Reporting\Resources\RelationshipInterface;
use App\Reporting\Resources\Relationships\ManyToOne;
use App\Reporting\Resources\Relationships\OneToOne;
use App\Reporting\Resources\Schema;
use App\Reporting\Resources\Table;
use App\Reporting\Resources\TableCollection;
use PHPUnit\Framework\TestCase;

class SchemaTest extends TestCase
{
	public function testGetTable()
	{
		$table = new Table('myTable', 'myTableAlias');
		$tableCollection = TableCollection::fromArray([$table]);

		$schema = new Schema($tableCollection, []);

		$retrievedTable = $schema->getTable('myTableAlias');

		$this->assertSame($table, $retrievedTable);
	}

	public function testHasDirectRelationship()
	{
		$table = new Table('myTable', 'myTableAlias');
		$relatedTable = new Table('relatedTable', 'relatedTableAlias');

		$relationships = [];
		$relationships[] = new OneToOne($table, $relatedTable, '');

		$tableCollection = TableCollection::fromArray([$table, $relatedTable]);

		$schema = new Schema($tableCollection, $relationships);

		$hasRelationship = $schema->hasDirectRelationship('myTableAlias', 'relatedTableAlias');

		$this->assertTrue($hasRelationship);
	}

	public function testGetRelationship()
	{
		$table = new Table('myTable', 'myTableAlias');
		$relatedTable = new Table('relatedTable', 'relatedTableAlias');

		$relationship = new OneToOne($table, $relatedTable, '');

		$tableCollection = TableCollection::fromArray([$table, $relatedTable]);

		$schema = new Schema($tableCollection, [$relationship]);

		$retrievedRelationship = $schema->getRelationship('myTableAlias', 'relatedTableAlias');

		$this->assertSame($relationship, $retrievedRelationship);
	}

	public function testHasRelationship()
	{
		$table = new Table('myTable', 'myTableAlias');
		$relatedTable = new Table('relatedTable', 'relatedTableAlias');
		$otherRelatedTable = new Table('otherRelatedTable', 'otherRelatedTableAlias');

		$relationships = [];
		$relationships[] = new OneToOne($table, $relatedTable, '');
		$relationships[] = new OneToOne($otherRelatedTable, $relatedTable, '');

		$tableCollection = TableCollection::fromArray([$table, $relatedTable, $otherRelatedTable]);

		$schema = new Schema($tableCollection, $relationships);

		$hasRelationship = $schema->hasRelationship('myTableAlias', 'otherRelatedTableAlias');

		$this->assertTrue($hasRelationship);
	}

	public function testGetRelationshipPath()
	{
		$table = new Table('myTable', 'myTableAlias');
		$relatedTable = new Table('relatedTable', 'relatedTableAlias');
		$otherRelatedTable = new Table('otherRelatedTable', 'otherRelatedTableAlias');

		$relationships = [];
		$relationships[] = new OneToOne($table, $relatedTable, '');
		$relationships[] = new OneToOne($otherRelatedTable, $relatedTable, '');

		$tableCollection = TableCollection::fromArray([$table, $relatedTable, $otherRelatedTable]);

		$schema = new Schema($tableCollection, $relationships);

		$path = $schema->getRelationshipPath('myTableAlias', 'otherRelatedTableAlias');

		$this->assertEquals(['myTableAlias', 'relatedTableAlias', 'otherRelatedTableAlias'], $path);
	}

	public function testGetQueryGroup()
	{
		$table = new Table('myTable', 'myTableAlias');
		$parentTable = new Table('parentTable', 'parentTableAlias');
		$grandParentTable = new Table('grandParentTable', 'grandParentTableAlias');
		$childTable = new Table('childTable', 'childTableAlias');
		$grandChildTable = new Table('grandChildTable', 'grandChildTableAlias');
		$parentOfGrandChildTable = new Table('parentOfGrandChildTable', 'parentOfGrandChildTableAlias');

		$relationships = [];
		$relationships[] = new ManyToOne($table, $parentTable, '');
		$relationships[] = new ManyToOne($parentTable, $grandParentTable, '');
		$relationships[] = new ManyToOne($childTable, $table, '');
		$relationships[] = new ManyToOne($grandChildTable, $childTable, '');
		$relationships[] = new ManyToOne($grandChildTable, $parentOfGrandChildTable, '');

		$tableCollection = TableCollection::fromArray([$table, $parentTable, $grandParentTable, $childTable, $grandChildTable, $parentOfGrandChildTable]);

		$schema = new Schema($tableCollection, $relationships);

		$testQueryGroup = $schema->getQueryGroup($table, $tableCollection);

		$expectedQueryGroup = new QueryGroup(
			$table,
			TableCollection::fromArray([$table, $parentTable, $grandParentTable]),
			[
				new QueryGroup($childTable, TableCollection::fromArray([$table, $childTable])),
				new QueryGroup($grandChildTable, TableCollection::fromArray([$table, $childTable, $grandChildTable, $parentOfGrandChildTable])),
			]
		);

		$this->assertEquals($expectedQueryGroup, $testQueryGroup);
	}
}