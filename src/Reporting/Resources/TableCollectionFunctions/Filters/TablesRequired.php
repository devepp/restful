<?php

namespace App\Reporting\Resources\TableCollectionFunctions\Filters;

use App\Reporting\ReportField;
use App\Reporting\Resources\Schema;
use App\Reporting\Resources\Table;
use App\Reporting\Resources\TableCollection;
use App\Reporting\Resources\TableCollectionFunctions\TableFilterInterface;
use App\Reporting\SelectionsInterface;

class TablesRequired implements TableFilterInterface
{
	/** @var Schema */
	private $schema;
	/** @var Table */
	private $rootTable;
	/** @var SelectionsInterface */
	private $selections;

	/**
	 * TablesRequired constructor.
	 * @param Schema $schema
	 * @param Table $rootTable
	 * @param SelectionsInterface $selections
	 */
	public function __construct(Schema $schema, Table $rootTable, SelectionsInterface $selections)
	{
		$this->schema = $schema;
		$this->rootTable = $rootTable;
		$this->selections = $selections;
	}


	public function __invoke(Table $table)
	{
		$fields = $this->selections->selectedFields();
		$directlyNeeded = $this->directlyNeeded($table, $fields);
		//TODO complete this

		throw new \Exception('this is not completed yet and does not work');
	}

	/**
	 * @param Table $table
	 * @param $fields
	 * @return bool
	 */
	protected function directlyNeeded(Table $table, $fields): bool
	{
		/** @var ReportField $field */
		foreach ($fields as $field) {
			if ($field->needsTable($table)) {
				return true;
			}
		}

		return false;
	}
}