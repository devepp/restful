<?php

namespace App\Reporting\Resources;

use App\Reporting\ReportFieldInterface;
use App\Reporting\ReportFilterInterface;

interface ResourceInterface
{
	/**
	 * @return Table
	 */
	public function table();
	/**
	 * @return string
	 */
	public function name();

	/**
	 * @return ReportFieldInterface[]
	 */
	public function availableFields();

	/**
	 * @return ReportFilterInterface[]
	 */
	public function availableFilters();
}