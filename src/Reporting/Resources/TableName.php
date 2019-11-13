<?php


namespace App\Reporting\Resources;


class TableName
{
	/** @var string */
	protected $name;

	/** @var string|null */
	protected $alias;

	/**
	 * TableName constructor.
	 * @param string $name
	 * @param string|null $alias
	 */
	public function __construct($name, $alias = null)
	{
		$this->name = $name;
		$this->alias = $alias;
	}


	public function __toString()
	{
		if ($this->alias === null || $this->alias === $this->name) {
			return $this->name;
		}
		return $this->name.' '.$this->alias;
	}

	public function name()
	{
		return $this->name;
	}

	public function alias()
	{
		if ($this->alias === null) {
			return $this->name;
		}
		return $this->alias;
	}

	public function aggregateName()
	{
		return $this->alias().'_aggregate';
	}
}