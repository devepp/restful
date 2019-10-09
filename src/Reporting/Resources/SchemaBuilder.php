<?php

namespace App\Reporting\Resources;

use App\Reporting\Resources\Relationships\ManyToOne;
use App\Reporting\Resources\Relationships\OneToOne;

class SchemaBuilder
{
	/** @var TableList */
	private $tables;

	private $relationships = [];

	public function __construct()
	{
		$this->tables = new TableList();
	}

	public function build()
	{
		return new Schema($this->tables, $this->relationships);
	}

	public function addManyToOneRelationship(Table $table, Table $secondTable, $condition)
	{
		$this->addTable($table);
		$this->addTable($secondTable);

		$this->relationships[] = new ManyToOne($table, $secondTable, $condition);
	}

//	public function addOneToManyRelationship(Table $table, Table $secondTable, $condition)
//	{
//
//	}

	public function addOneToOneRelationship(Table $table, Table $secondTable, $condition)
	{
		$this->addTable($table);
		$this->addTable($secondTable);

		$this->relationships[] = new OneToOne($table, $secondTable, $condition);
	}

	private function addTable(Table $table)
	{
		$this->tables->addTable($table);
	}
}