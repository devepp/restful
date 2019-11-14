<?php

namespace App\Reporting;

use App\Reporting\Filters\Constrains;
use App\Reporting\Filters\Constraints\AbstractConstraint;

class RequestedFilter
{
	private $reportFieldId;

	/** @var Constrains */
	private $constraint;

	/** @var array */
	private $inputs;

	/**
	 * RequestedFilter constructor.
	 * @param $reportFieldId
	 * @param Constrains $constraint
	 * @param array $inputs
	 */
	public function __construct($reportFieldId, Constrains $constraint, $inputs)
	{
		$this->reportFieldId = $reportFieldId;
		$this->constraint = $constraint;
		$this->inputs = $inputs;
	}

	public static function fromRequestDataArray($requestFilterData)
	{
		$reportFieldId = $requestFilterData['id'];
		$constraint = AbstractConstraint::getConstraint($requestFilterData['constraint']['name']);
		$inputs = $constraint->inputArrayFromRequestData($requestFilterData['constraint']);

		return new self($reportFieldId, $constraint, $inputs);
	}

	/**
	 * @return mixed
	 */
	public function reportFieldId()
	{
		return $this->reportFieldId;
	}

	/**
	 * @return Constrains
	 */
	public function constraint()
	{
		return $this->constraint;
	}

	/**
	 * @return array
	 */
	public function inputs()
	{
		return $this->inputs;
	}
}