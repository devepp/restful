<?php
/**
 * Created by PhpStorm.
 * User: Paul.Epp
 * Date: 1/17/2019
 * Time: 11:01 AM
 */

namespace App\Reporting\Processing;

use App\Reporting\Resources\Table;
use App\Reporting\Resources\TableCollection;

class QueryPath
{
	/** @var TableCollection */
	protected $tables;

	/**
	 * QueryPath constructor.
	 * @param TableCollection $tables
	 */
	public function __construct(TableCollection $tables)
	{
		$this->tables = $tables;
	}

	/**
	 * @param Table $table
	 * @return QueryPath
	 */
	public function newQueryPath(Table $table)
	{
		$new_path = new QueryPath($this->tables);

		$new_path->addTable($table);

		return $new_path;
	}

	/**
	 * @param Table $table
	 */
	public function addTable(Table $table)
	{
		$this->tables->addTable($table);
	}


}