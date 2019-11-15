<?php

namespace App\Reporting\Request;

use App\Reporting\Filters\Constrains;
use App\Reporting\Filters\Constraints\AbstractConstraint;

class RequestedFilter
{
	private $reportFieldId;

	/** @var Constrains */
	private $constraint;

	/** @var array */
	private $inputs;

	/** @var string */
	private $label;

	/**
	 * RequestedFilter constructor.
	 * @param $reportFieldId
	 * @param Constrains $constraint
	 * @param array $inputs
	 * @param string $label
	 */
	public function __construct($reportFieldId, Constrains $constraint, $inputs, $label)
	{
		$this->reportFieldId = $reportFieldId;
		$this->constraint = $constraint;
		$this->inputs = $inputs;
		$this->label = $label;
	}

	public static function fromRequestDataArray($requestFilterData)
	{
		$reportFieldId = $requestFilterData['id'];
		$constraint = AbstractConstraint::getConstraint($requestFilterData['constraint']['name']);
		$inputs = $constraint->inputArrayFromRequestData($requestFilterData['constraint']);
		$label = $requestFilterData['label'];

		return new self($reportFieldId, $constraint, $inputs, $label);
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

	/**
	 * @return string
	 */
	public function label()
	{
		return $this->label;
	}
}