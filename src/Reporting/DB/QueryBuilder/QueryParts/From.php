<?php

namespace App\Reporting\DB\QueryBuilder\QueryParts;


class From
{
	private $from;

	/**
	 * From constructor.
	 * @param $from
	 */
	public function __construct($from)
	{
		$this->from = $from;
	}

	public function __toString()
	{
		return ' FROM '.$this->from;
	}


	/**
	 * @return mixed
	 */
	public function getFrom()
	{
		return $this->from;
	}


}