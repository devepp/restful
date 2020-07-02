<?php

namespace App\WorkOrders\Domain\Model\WorkOrders\Statuses;

class Cancelled extends WorkOrderStatus
{
	public function __construct()
	{
		parent::__construct('cancelled');
	}

	public function getAvailableStatusChanges()
	{
		return [];
	}

}