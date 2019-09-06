<?php
/**
 * Created by PhpStorm.
 * User: Paul.Epp
 * Date: 1/8/2019
 * Time: 10:34 AM
 */

namespace App\Reporting;

use JsonSerializable;

class SelectableField implements JsonSerializable
{
	/** @var string */
	protected $name;

	/** @var string */
	protected $label;

	/**
	 * SelectableField constructor.
	 * @param string $name
	 * @param string $label
	 */
	public function __construct($name, $label)
	{
		$this->name = $name;
		$this->label = $label;
	}

	/**
	 * @return array
	 */
	public function jsonSerialize()
	{
		return [
			'name' => $this->name(),
			'label' => $this->label(),
			'aggregate_options' => $this->label(),
		];
	}

	/**
	 * @return string
	 */
	public function name()
	{
		return $this->name;
	}

	/**
	 * @return string
	 */
	public function label()
	{
		return $this->label;
	}


}