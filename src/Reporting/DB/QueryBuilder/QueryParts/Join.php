<?php

namespace App\Reporting\DB\QueryBuilder\QueryParts;


class Join
{
	private $table;
	private $on;
	private $type;

	/**
	 * Join constructor.
	 * @param $table
	 * @param $on
	 * @param $type
	 */
	public function __construct($table, $on, $type)
	{
		$this->table = $table;
		$this->on = $on;
		$this->type = $type;
	}

	/**
	 * @return mixed
	 */
	public function getTable()
	{
		return $this->table;
	}

	/**
	 * @return mixed
	 */
	public function getOn()
	{
		return $this->on;
	}

	/**
	 * @return mixed
	 */
	public function getType()
	{
		return $this->type;
	}


}