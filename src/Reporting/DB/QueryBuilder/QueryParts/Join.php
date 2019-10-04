<?php

namespace App\Reporting\DB\QueryBuilder\QueryParts;

abstract class Join implements JoinInterface
{
	const INNER = 'INNER';
	const LEFT = 'LEFT';
	const RIGHT = 'RIGHT';
	const OUTER = 'OUTER';
	const CROSS = 'CROSS';

	const TYPES = [
		self::INNER,
		self::LEFT,
		self::RIGHT,
		self::OUTER,
		self::CROSS,
	];


	abstract public function getStatementExpression();
	abstract public function getParameters();

	public function __toString()
	{
		return $this->getStatementExpression();
	}
}