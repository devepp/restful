<?php

namespace App\Reporting\Request;

class Sort
{
	const ASC = 'ASC';
	const DESC = 'DESC';

	private $fieldId;
	private $direction;

	public function __construct($fieldId, $direction = self::ASC)
	{
		$direction = \strtoupper($direction);

		if ($direction !== self::ASC && $direction !== self::DESC) {
			throw new \InvalidArgumentException('invalid direction parameter direction "' . $direction . '" used.');
		}

		$this->fieldId = $fieldId;
		$this->direction = $direction;
	}

	public function fieldId()
	{
		return $this->fieldId;
	}

	public function direction()
	{
		return $this->direction;
	}
}