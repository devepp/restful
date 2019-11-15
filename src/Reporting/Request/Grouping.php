<?php

namespace App\Reporting\Request;

class Grouping
{
	private $fieldId;

	/**
	 * RequestedGrouping constructor.
	 * @param $fieldId
	 */
	public function __construct($fieldId)
	{
		$this->fieldId = $fieldId;
	}

	/**
	 * @return mixed
	 */
	public function fieldId()
	{
		return $this->fieldId;
	}
}