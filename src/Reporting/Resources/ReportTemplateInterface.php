<?php


namespace App\Reporting\Resources;

use App\Reporting\ReportFieldInterface;
use App\Reporting\ReportFilterInterface;

interface ReportTemplateInterface
{

	public function availableRelatedResources();

	/**
	 * @return array
	 */
	public function nestedFields();

	/**
	 * @return ReportFieldInterface[]
	 */
	public function availableFields();

	/**
	 * @return array
	 */
	public function nestedFilters();

	/**
	 * @return ReportFilterInterface[]
	 */
	public function availableFilters();
}