<?php

namespace App\Reporting\Common;

class Values extends \ArrayIterator
{
	public function __construct($array, int $flags = 0)
	{
		parent::__construct($array, $flags);
	}

	public static function fromArray($array)
	{
		return new self($array);
	}

	public static function emptyValues()
	{
		return new self([]);
	}

	/**
	 * return value or default
	 *
	 * @param $index
	 * @param null $default
	 * @return Values|mixed|null
	 */
	public function value($index, $default = null)
	{
		if (parent::offsetExists($index)) {
			return parent::offsetGet($index);
		}

		return $default;
	}

	/**
	 * @param $index
	 * @return mixed
	 */
	public function valueOrFail($index)
	{
		return parent::offsetGet($index);
	}
}