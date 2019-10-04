<?php

namespace App\Reporting\DB\QueryBuilder\QueryParts;

use InvalidArgumentException;

class OrderBy implements OrderByInterface
{
	const ASC = 'ASC';
	const DESC = 'DESC';

	const DIRECTIONS = [
		self::ASC,
		self::DESC
	];

	private $field;
	private $direction;

	/**
	 * OrderBy constructor.
	 * @param $field
	 * @param $direction
	 */
	public function __construct($field, $direction = 'ASC')
	{
		if (!in_array($direction, self::DIRECTIONS)) {
			throw new InvalidArgumentException('Direction must be either ASC or DESC. "'.$direction.'" was set.');
		}

		$this->field = $field;
		$this->direction = $direction;
	}

	public function __toString()
	{
		return $this->getStatementExpression();
	}

	/**
	 * @return mixed
	 */
	public function getField()
	{
		return $this->field;
	}

	/**
	 * @return mixed
	 */
	public function getDirection()
	{
		return $this->direction;
	}

	public function getStatementExpression()
	{
		return $this->field.' '.$this->direction;
	}

	public function getParameters()
	{
		return [];
	}


}