<?php

namespace App\Reporting\DB\QueryBuilder\QueryParts;

use InvalidArgumentException;

class TableJoin extends Join
{
	/** @var TableExpression */
	private $table;
	private $on;
	private $type;

	/**
	 * TableJoin constructor.
	 * @param TableExpression $table
	 * @param $on
	 * @param $type
	 */
	public function __construct(TableExpression $table, $on, $type)
	{
		if (!in_array(strtoupper($type), Join::TYPES)) {
			throw new InvalidArgumentException('Join Type must be either one of the following : '.implode(',', Join::TYPES).'. "'.$type.'" was set.');
		}

		$this->table = $table;
		$this->on = $on;
		$this->type = strtoupper($type);
	}

	/**
	 * @return string
	 */
	public function getStatementExpression()
	{
		return $this->type.' JOIN '.$this->table.' ON '.$this->on;
	}

	/**
	 * @return array
	 */
	public function getParameters()
	{
		return [];
	}


}