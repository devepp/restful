<?php

namespace App\Reporting\DB;


class Query
{
	private $statement;

	private $parameters = [];

	/**
	 * Query constructor.
	 * @param $statement
	 * @param array $parameters
	 */
	public function __construct($statement, array $parameters = [])
	{
		$this->statement = $statement;
		$this->parameters = $parameters;
	}

	/**
	 * @return mixed
	 */
	public function getStatement()
	{
		return $this->statement;
	}

	/**
	 * @return array
	 */
	public function getParameters()
	{
		return $this->parameters;
	}

}