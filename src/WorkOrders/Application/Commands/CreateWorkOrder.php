<?php

namespace App\WorkOrders\Application\Commands;

class CreateWorkOrder
{
	private $type;

	private $subject;

	private $description;

	/**
	 * CreateWorkOrder constructor.
	 * @param $type
	 * @param $subject
	 * @param $description
	 */
	public function __construct($type, $subject, $description)
	{
		$this->type = $type;
		$this->subject = $subject;
		$this->description = $description;
	}

	/**
	 * @return mixed
	 */
	public function getType()
	{
		return $this->type;
	}

	/**
	 * @return mixed
	 */
	public function getSubject()
	{
		return $this->subject;
	}

	/**
	 * @return mixed
	 */
	public function getDescription()
	{
		return $this->description;
	}


}