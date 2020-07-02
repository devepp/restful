<?php

namespace App\WorkOrders\Domain\Model\WorkOrders\Statuses;

class Active extends WorkOrderStatus
{
	public function __construct()
	{
		parent::__construct('active');
	}

	public function getAvailableStatusChanges()
	{
		return [
			new Cancelled()
		];
	}


}