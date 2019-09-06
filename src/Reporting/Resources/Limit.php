<?php

namespace App\Reporting\Resources;

class Limit
{
	/** @var int */
	private $numberOfRecords;

	/** @var int */
	private $offset;

	/**
	 * Limit constructor.
	 * @param int $numberOfRecords
	 * @param int $offset
	 */
	public function __construct($numberOfRecords, $offset)
	{
		$this->numberOfRecords = $numberOfRecords;
		$this->offset = $offset;
	}

	/**
	 * @return int
	 */
	public function numberOfRecords()
	{
		return $this->numberOfRecords;
	}

	/**
	 * @return int
	 */
	public function offset()
	{
		return $this->offset;
	}

	/**
	 * @return int
	 */
	public function sql()
	{
		return 'LIMIT '.$this->offset().','.$this->numberOfRecords();
	}




}