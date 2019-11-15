<?php


namespace App\Reporting\Resources;

use App\Reporting\DB\DbInterface;
use App\Reporting\ReportFieldCollection;
use App\Reporting\ReportFilterCollection;
use App\Reporting\Request\ReportRequest;
use App\Reporting\TabularData;

interface ReportTemplateInterface
{

	/**
	 * @return ReportFieldCollection
	 */
	public function fields();

	/**
	 * @return ReportFilterCollection
	 */
	public function filters();

	/**
	 * @param DbInterface $db
	 * @param ReportRequest $request
	 * @return TabularData
	 */
	public function getData(DbInterface $db, ReportRequest $request);
}