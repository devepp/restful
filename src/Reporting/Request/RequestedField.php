<?php

namespace App\Reporting\Request;

use App\Reporting\Selectables\AbstractSelectable;

class RequestedField
{
	private $reportFieldId;

	/** @var AbstractSelectable */
	private $modifier;

	/** @var string */
	private $label;

	/**
	 * RequestedField constructor.
	 * @param $reportFieldId
	 * @param AbstractSelectable $modifier
	 * @param $label
	 */
	public function __construct($reportFieldId, AbstractSelectable $modifier, $label)
	{
		$this->reportFieldId = $reportFieldId;
		$this->modifier = $modifier;
		$this->label = $label;
	}

	public static function fromRequestDataArray($requestFieldData)
	{
		$reportFieldId = $requestFieldData['id'];
		$modifier = AbstractSelectable::getSelectable($requestFieldData['modifier']);
		$label = $requestFieldData['label'];

		return new self($reportFieldId, $modifier, $label);
	}

	/**
	 * @return mixed
	 */
	public function reportFieldId()
	{
		return $this->reportFieldId;
	}

	/**
	 * @return AbstractSelectable
	 */
	public function modifier()
	{
		return $this->modifier;
	}

	/**
	 * @return mixed
	 */
	public function label()
	{
		return $this->label;
	}
}