<?php

namespace App\WorkOrders\Domain\Model\WorkOrders;

interface WorkOrderRepositoryInterface
{
	public function get($workOrderId): WorkOrder;

	public function add(WorkOrder $workOrder);

	public function remove(WorkOrder $workOrder);
}