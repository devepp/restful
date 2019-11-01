<?php

namespace App\Reporting\Resources;

use SeekableIterator;
use OutOfBoundsException;
use usort;

class TableCollection implements SeekableIterator
{
	private $position = 0;

	/** @var Table[] */
	protected $tables = [];

	/** @var string[] */
	protected $aliases = [];

	/**
	 * TableCollection constructor.
	 * @param array $tables
	 */
	public function __construct($tables = [])
	{
		$this->position = 0;
		foreach ($tables as $table) {
			$this->indexTable($table);
		}
	}

	public static function fromArray($tables)
	{
		return new self($tables);
	}

	public function __debugInfo()
	{
		return $this->aliases;
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

	public function seek($position)
	{
		if (!isset($this->aliases[$position])) {
			throw new OutOfBoundsException("invalid seek position ($position)");
		}

		$this->position = $position;
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

	/**
	 * @return bool
	 */
	public function empty()
	{
		return empty($this->tables);
	}

	public function addTable(Table $table)
	{
		$clone = clone $this;

		$clone->indexTable($table);

		return $clone;
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

	public function aliases()
	{
		return $this->aliases;
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
			$this->indexTable($table);
		}
	}

	/**
	 * @param TableCollection $tableCollection
	 * @return TableCollection
	 */
	public function merge(TableCollection $tableCollection)
	{
		$tables = \array_merge($this->getTables(), $tableCollection->getTables());
		return new self($tables);
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

	public function sort(callable $sortFunction)
	{
		$tables = $this->tables;
		usort($tables, $sortFunction);
		return self::fromArray($tables);
	}

	public function map(callable $mapFunction)
	{
		return array_map($mapFunction, $this->tables);
	}

	public function filter(callable $filterFunction, $flag = 0)
	{
		return new self(array_filter($this->tables, $filterFunction, $flag));
	}

	public function reduce(callable $reducingFunction, $initialValue = null)
	{
		return array_reduce($this->tables, $reducingFunction, $initialValue);
	}

	private function indexTable(Table $table)
	{
		$this->tables[$table->alias()] = $table;
		$this->aliases = array_keys($this->tables);
	}

}