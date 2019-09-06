<?php
/**
 * Created by PhpStorm.
 * User: Paul.Epp
 * Date: 1/17/2019
 * Time: 11:17 AM
 */

namespace App\Reporting\Resources;

use Iterator;

class TableList implements Iterator
{
	private $position = 0;

	/** @var Table[] */
	protected $tables = [];

	/** @var string[] */
	protected $aliases = [];

	/**
	 * TableList constructor.
	 * @param Table[] $tables
	 */
	public function __construct($tables = [])
	{
		$this->position = 0;
		foreach ($tables as $table) {
			$this->addTable($table);
		}
	}

	public function __debugInfo() {
		return [
//			'tables' => $this->tables,
			'aliases' => $this->aliases,
		];
	}

	public function current()
	{
		return $this->tables[$this->aliases[$this->position]];
	}

	public function next()
	{
		++$this->position;
	}

	public function key()
	{
		return $this->aliases[$this->position];
	}

	public function valid()
	{
		return isset($this->aliases[$this->position]);
	}

	public function rewind()
	{
		$this->position = 0;
	}

	/**
	 * @return Table
	 */
	public function first()
	{
		return $this->tables[$this->aliases[0]];
	}

	/**
	 * @return Table
	 */
	public function last()
	{
		return $this->tables[$this->aliases[count($this->aliases) - 1]];
	}

	/**
	 * @return Table
	 */
	public function count()
	{
		return count($this->tables);
	}


	public function addTable(Table $table)
	{
		$this->tables[$table->alias()] = $table;
		$this->aliases = array_keys($this->tables);
	}

	/**
	 * @param $table_alias
	 * @return Table
	 */
	public function getTable($table_alias)
	{
		if ($this->hasAlias($table_alias)) {
			return $this->tables[$table_alias];
		}
	}

	/**
	 * @return Table[]
	 */
	public function getTables()
	{
		return array_values($this->tables);
	}

	public function hasAlias($table_alias)
	{
		return isset($this->tables[$table_alias]);
	}

	public function hasTable(Table $table)
	{
		return $this->hasAlias($table->alias());
	}

	/**
	 * @param Table[] $tables
	 */
	public function mergeTables($tables)
	{
		foreach ($tables as $table) {
			$this->addTable($table);
		}
	}

	/**
	 * @param TableList $table_list
	 */
	public function merge(TableList $table_list)
	{
		$this->mergeTables($table_list->getTables());
	}

	public function findFirstMatching($search_aliases, $reverse_order = false)
	{
		$table_aliases = $reverse_order ? array_reverse(array_keys($this->tables)) : array_keys($this->tables);
		foreach ($table_aliases as $alias) {
			foreach ($search_aliases as $search_alias) {
				if ($search_alias == $alias) {
					return $this->tables[$alias];
				}
			}
		}
	}
}