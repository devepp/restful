<?php

namespace App\WorkOrders\Domain\Model\WorkOrders;

use App\WorkOrders\Domain\Model\WorkOrders\Statuses\Active;
use App\WorkOrders\Domain\Model\WorkOrders\Statuses\Cancelled;
use App\WorkOrders\Domain\Model\WorkOrders\Statuses\WorkOrderStatus;

class WorkOrder
{
	private $id;

	private $type;

	/** @var WorkOrderStatus */
	private $status;

	private $number;

	private $description;

	private $start;

	private $end;

	private $dueDate;

	/** @var Job[] */
	private $jobs;

	/**
	 * WorkOrder constructor.
	 * @param $type
	 */
	public function __construct($type, $subject, $description)
	{
		$this->type = $type;
		$this->jobs[] = new Job($subject, $description);
		$this->status = new Active();
	}

	public function addJob($subject, $description)
	{
		foreach ($this->jobs as $job) {
			if ($job->isActive()) {
				throw new \Exception('active job');
			}
		}

		$this->jobs[] = new Job($subject, $description);
//		$this->jobs[] = $job;
	}

	public function cancel()
	{
		if (\in_array(new Cancelled(), $this->status->getAvailableStatusChanges()) === false) {
			throw new \Exception('no');
		}
		$this->status = new Cancelled();
	}

	/**
	 * @return mixed
	 */
	public function setType($type)
	{
//		if ()
		return $this->description;
	}
}