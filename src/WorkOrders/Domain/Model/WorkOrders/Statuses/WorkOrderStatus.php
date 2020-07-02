<?php

namespace App\WorkOrders\Domain\Model\WorkOrders\Statuses;

abstract class WorkOrderStatus
{
	private $code;

	/**
	 * WorkOrderStatus constructor.
	 */
	public function __construct($code)
	{
		$this->code = $code;
	}

	public function canChangeStatusTo($code)
	{
		foreach ($this->getAvailableStatusChanges() as $availableStatus) {
			if ($code === $availableStatus->getCode()) {
				return true;
			}
		}
		return false;
	}

	/**
	 * @return mixed
	 */
	public function getCode()
	{
		return $this->code;
	}

	abstract public function getAvailableStatusChanges();
}