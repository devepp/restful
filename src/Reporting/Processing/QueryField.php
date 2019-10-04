<?php


namespace App\Reporting\Processing;


class QueryField
{
	/** @var string */
	private $selectString;

	/** @var string|null */
	private $alias;

	/**
	 * QueryField constructor.
	 * @param string $selectField
	 * @param null|string $alias
	 */
	public function __construct($selectField, $alias = null)
	{
		$this->selectString = $selectField;
		$this->alias = $alias;
	}

	public function __toString()
	{
		return $this->asString();
	}

	public function asString()
	{
		$alias = $this->alias ? ' AS '.$this->alias : '';

		return $this->selectString.$alias;
	}

	public function selectString()
	{
		return $this->selectString;
	}

	public function alias()
	{
		return $this->alias;
	}


}