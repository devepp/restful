<?php

namespace App\Reporting\DB\Schema;

class Field implements FieldInterface
{
	const CHAR = 'CHAR';
	const VARCHAR = 'CHAR';
	const TINYTEXT = 'CHAR';
	const TEXT = 'CHAR';
	const BLOB = 'CHAR';

	const TYPES = [

	];

	/** @var string */
	private $name;

	/**
	 * Field constructor.
	 * @param $name
	 */
	public function __construct($name)
	{
		$this->name = $name;
	}

	public function __toString()
	{
		return $this->name;
	}

}