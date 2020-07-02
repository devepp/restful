<?php

namespace App\WorkOrders\Application\Handlers;

use App\WorkOrders\Application\Commands\CreateWorkOrder;
use App\WorkOrders\Domain\Model\WorkOrders\WorkOrder;
use App\WorkOrders\Domain\Model\WorkOrders\WorkOrderRepositoryInterface;

class CreateWorkOrderHandler
{
	/** @var WorkOrderRepositoryInterface */
	private $workOrderRepository;

	/**
	 * CreateWorkOrderHandler constructor.
	 * @param WorkOrderRepositoryInterface $workOrderRepository
	 */
	public function __construct(WorkOrderRepositoryInterface $workOrderRepository)
	{
		$this->workOrderRepository = $workOrderRepository;
	}


	public function __invoke(CreateWorkOrder $command)
	{
		$workOrder = new WorkOrder($command->getType(), $command->getSubject(), $command->getDescription());

		//dispatch events

		$this->workOrderRepository->add($workOrder);

		return $workOrder;
	}
}