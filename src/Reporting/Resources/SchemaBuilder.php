<?php

namespace App\Reporting\Resources;

use App\Reporting\Resources\Relationships\ManyToOne;
use App\Reporting\Resources\Relationships\OneToOne;

class SchemaBuilder
{
	/** @var TableCollection */
	private $tables;

	private $relationships = [];

	public function __construct()
	{
		$this->tables = new TableCollection();
	}

	public function build()
	{
		return new Schema($this->tables, $this->relationships);
	}

//	public function addManyToOneRelationship(Table $table, Table $secondTable, $condition)
//	{
//		$clone = clone $this;
//
//		$clone->addTable($table);
//		$clone->addTable($secondTable);
//
//		$clone->relationships[] = new ManyToOne($table, $secondTable, $condition);
//
//		return $clone;
//	}
//
//	public function addOneToOneRelationship(Table $table, Table $secondTable, $condition)
//	{
//		$clone = clone $this;
//
//		$clone->addTable($table);
//		$clone->addTable($secondTable);
//
//		$clone->relationships[] = new OneToOne($table, $secondTable, $condition);
//
//		return $clone;
//	}

	public function addTable(Table $table)
	{
		$clone = clone $this;
		$clone->tables = $clone->tables->addTable($table);
		return $clone;
	}
}