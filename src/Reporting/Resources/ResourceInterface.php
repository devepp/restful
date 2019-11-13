<?php

namespace App\Reporting\Resources;

use App\Reporting\ReportFieldCollection;
use App\Reporting\ReportFilterCollection;

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
	 * @return ReportFieldCollection
	 */
	public function availableFields();

	/**
	 * @return ReportFilterCollection
	 */
	public function availableFilters();
}