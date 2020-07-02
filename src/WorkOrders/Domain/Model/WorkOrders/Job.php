<?php

namespace App\WorkOrders\Domain\Model\WorkOrders;

class Job
{
	private $id;

	private $subject;

	private $description;

	/** @var bool */
	private $isActive;

	/**
	 * Job constructor.
	 * @param $subject
	 * @param $description
	 */
	public function __construct($subject, $description)
	{
		$this->subject = $subject;
		$this->description = $description;
		$this->isActive = true;
	}

	/**
	 * @return bool
	 */
	public function isActive(): bool
	{
		return $this->isActive;
	}
}