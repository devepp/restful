<?php

namespace Tests\Unit\Reporting\Resources;

use App\Reporting\DatabaseFields\BooleanField;
use App\Reporting\DatabaseFields\DateField;
use App\Reporting\DatabaseFields\DateTimeField;
use App\Reporting\DatabaseFields\PrimaryKey;
use App\Reporting\DatabaseFields\StringField;
use App\Reporting\Resources\Table;
use App\Reporting\Resources\TableBuilder;
use App\Reporting\Resources\TableName;
use PHPUnit\Framework\TestCase;

class TableBuilderTest extends TestCase
{
	public function testBuild()
	{
		$builder = new TableBuilder('assets');

		$table = $builder->build();

		$this->assertEquals('assets', $table->name());
	}

	public function testSetAlias()
	{
		$builder = new TableBuilder('assets');

		$table = $builder->setAlias('ass')->build();

		$this->assertEquals('ass', $table->alias());
	}

	public function testSetPrimaryKey()
	{
		$builder = new TableBuilder('assets');

		$table = $builder->setPrimaryKey('id')->build();

		$field = $table->dbField('id');

		$this->assertEquals(new PrimaryKey('id'), $field);
	}

	public function testAddStringField()
	{
		$builder = new TableBuilder('assets');

		$table = $builder->addStringField('name')->build();

		$field = $table->dbField('name');

		$this->assertEquals(new StringField('name'), $field);
	}

	public function testAddNumberField()
	{
		$builder = new TableBuilder('assets');

		$table = $builder->addStringField('name')->build();

		$field = $table->dbField('name');

		$this->assertEquals(new StringField('name'), $field);
	}

	public function testAddDateField()
	{
		$builder = new TableBuilder('assets');

		$table = $builder->addDateField('issued_date')->build();

		$field = $table->dbField('issued_date');

		$this->assertEquals(new DateField('issued_date'), $field);
	}

	public function testAddDateTimeField()
	{
		$builder = new TableBuilder('assets');

		$table = $builder->addDateTimeField('created_at')->build();

		$field = $table->dbField('created_at');

		$this->assertEquals(new DateTimeField('created_at'), $field);
	}

	public function testAddBooleanField()
	{
		$builder = new TableBuilder('assets');

		$table = $builder->addBooleanField('has_it')->build();

		$field = $table->dbField('has_it');

		$this->assertEquals(new BooleanField('has_it'), $field);
	}

	public function testAddManyToOneRelationship()
	{
		$relation = new Table(new TableName('relation'), [], []);

		$builder = new TableBuilder('table');

		$builder = $builder->addManyToOneRelationship($relation->tableName(), 'relation_id', 'table.relation_id = relation.id');
		$table = $builder->build();

		$this->assertTrue($table->relatedTo($relation->alias()), 'table is related to relation');

//		$this->assertTrue($relation->relatedTo($table->alias()), 'relation is related to table');
	}

//	public function testAddOneToOneRelationship(TableName $tableName, $foreignKey, $condition)
//	{
//		$builder = new TableBuilder('assets');
//
//		$table = $builder->testAddManyToOneRelationship($tableName, $foreignKey, $condition);
//
//		$this->assertEquals('assets', $table->name());
//	}
}