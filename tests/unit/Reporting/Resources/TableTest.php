<?php

namespace Tests\Unit\Reporting\Resources;

use App\Reporting\DatabaseFields\StringField;
use App\Reporting\Resources\Relationships\ManyToOne;
use App\Reporting\Resources\Table;
use App\Reporting\Resources\TableCollection;
use App\Reporting\Resources\TableName;
use PHPUnit\Framework\TestCase;
use Tests\Doubles\GetTable;

class TableTest extends TestCase
{
	use GetTable;

	public function testToString()
	{
		$tableName = new TableName('as_assets', 'assets');
		$table = new Table($tableName, [],[]);

		$this->assertEquals($tableName->__toString(), $table->__toString());
	}

	public function testTableName()
	{
		$tableName = new TableName('as_assets', 'assets');
		$table = new Table($tableName, [],[]);

		$this->assertSame($tableName, $table->tableName());
	}

	public function testName()
	{
		$tableName = new TableName('as_assets', 'assets');
		$table = new Table($tableName, [],[]);

		$this->assertSame($tableName->name(), $table->name());
	}

//	public function testAggregateName()
//	{
//		return $this->name->aggregateName();
//	}

//	public function testAlias()
//	{
//		return $this->name->alias();
//	}
//
//	public function testPrimaryKey()
//	{
//		foreach ($this->fields as $field) {
//			if ($field instanceof PrimaryKey) {
//				return $field;
//			}
//		}
//
//		return null;
//	}
//
//	public function testGetFields()
//	{
//		return array_values($this->fields);
//	}
//
//	public function testHasField(string $fieldName)
//	{
//		return isset($this->fields[$fieldName]);
//	}
//
//	public function testDbField($fieldName)
//	{
//		if (isset($this->fields[$fieldName])) {
//			return $this->fields[$fieldName];
//		}
//
//		throw new \LogicException($fieldName.' does not exist on table '.$this->name());
//	}
//
//	public function testGetReportFields()
//	{
//		$report_fields = [];
//		foreach ($this->fields as $databaseField) {
//			if ($databaseField->useAsField()) {
//				$report_fields[] = new ReportField($this, $databaseField);
//			}
//		}
//
//		return $report_fields;
//	}
//
//	public function testRelatedTo($tableAlias)
//	{
//		return isset($this->relationships[$tableAlias]);
//	}
//
//	public function testHasOne($tableAlias)
//	{
//		if ($this->relatedTo($tableAlias) === false) {
//			$thisTableName = $this->name->name();
//			throw new \LogicException("tableAlias `$tableAlias` not related to table `$thisTableName`");
//		}
//
//		$relationship = $this->relationships[$tableAlias];
//
//		return $relationship->tableHasOne($this->alias(), $tableAlias);
//	}

	public function testPathTo()
	{
		$table1 = $this->getTable('table1');
		$table2 = $this->getTable('table2', ['manyToOne' => 'table1']);
		$table3 = $this->getTable('table3', ['manyToOne' => 'table2']);

		$tableCollection = TableCollection::fromArray([$table1, $table2, $table3]);

		$this->assertEquals($tableCollection, $table1->pathTo($table3, $tableCollection));
	}

	public function testSameNodeAs()
	{
		$table1 = $this->getTable('table1');
		$table2 = $this->getTable('table2', ['manyToOne' => 'table1']);

		$tableCollection = TableCollection::fromArray([$table1, $table2]);

		$this->assertTrue($table2->sameNodeAs($table1, $tableCollection));
	}

	public function testJoinCondition()
	{
		$table1 = $this->getTable('table1');
		$table2 = $this->getTable('table2', ['manyToOne' => 'table1']);

		$this->assertEquals('table2Alias.table1_id = table1Alias.id', $table1->joinCondition($table2));

		$this->assertEquals('table2Alias.table1_id = table1Alias.id', $table2->joinCondition($table1));
	}
}